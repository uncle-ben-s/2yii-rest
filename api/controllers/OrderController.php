<?php

namespace api\controllers;

use api\services\OrderManager;
use api\services\PaymentService;
use common\models\Ticket;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;

class OrderController extends ActiveController
{
    public $modelClass = 'common\models\Order';

    public function behaviors(){
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => [
                'authMethods' => [
                    HttpBasicAuth::class,
                    HttpBearerAuth::class,
                ]
            ],
            'access' => [
                'class' => AccessControl::class,
//                'only' => ['create', 'view'],
                'rules' => [
//                    [
//                        'allow' => false,
//                        'actions' => ['index'/*, 'view'*/],
//                        'roles' => ['@'],
//                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'view', 'reserve', 'buy'],
                        'roles' => ['@'],
                    ],
                ],
            ]
        ]);
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['view']);
        return $actions;
    }

    /**
     * @api {post} /orders Create
     * @apiName actionCreate
     * @apiGroup Order
     *
     * @apiSuccess {Number} id Order unique ID.
     * @apiSuccess {Number} client_id  Client ID.
     * @apiSuccess {String} status  Order status .
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "id": "1",
     *         "client_id": "1",
     *         "status": "open",
     *     }
     *
     */
    public function actionCreate(){
        $clientId = Yii::$app->getUser()->id;

        $om = OrderManager::getInstanceByClientId($clientId);

        if(is_null($om)){
            $om = OrderManager::getCreatedInstance($clientId);
            Yii::$app->getResponse()->setStatusCode(201);
        }

        return $om->getModel();
    }

    /**
     * @api {get} /orders/:id Details
     * @apiName actionView
     * @apiGroup Order
     *
     * @apiParam {Number} id  Order ID.
     *
     * @apiSuccess {Number} id Order unique ID.
     * @apiSuccess {Number} client_id  Client ID.
     * @apiSuccess {String} status  Order status .
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "id": "1",
     *         "client_id": "1",
     *         "status": "open",
     *     }
     *
     * @apiError OrderClosed  Order by this order_id is closed.
     * @apiError OrderNotFound  Order by this order_id not found.
     * @apiError OrderBelongsToAnotherClient  This order belongs to another client.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 400 Bad Request
     *     {
     *         "error": "OrderClosed"
     *     }
     */
    public function actionView($id){
        $om = OrderManager::getInstance($id);

        return array_merge(['ticket_cnt' => $om->getTicketsCount(), 'amount' => $om->getAmount()], $om->getModel()->toArray());
    }

    /**
     * @api {get} /orders/:id/reserve Reserve
     * @apiName actionReserve
     * @apiGroup Order
     *
     * @apiParam {Number} id  Order ID.
     *
     * @apiSuccess {Number} id Order unique ID.
     * @apiSuccess {Number} client_id  Client ID.
     * @apiSuccess {String} status  Order status .
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "id": "1",
     *         "client_id": "1",
     *         "status": "open",
     *     }
     *
     * @apiError OrderClosed  Order by this order_id is closed.
     * @apiError OrderNotFound  Order by this order_id not found.
     * @apiError OrderBelongsToAnotherClient  This order belongs to another client.
     * @apiError OrderWithoutTickets  Order without tickets.
     * @apiError PaymentFailed  Payment failed.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 400 Bad Request
     *     {
     *         "error": "OrderClosed"
     *     }
     *or
     *     HTTP/1.1 402 Payment Required
     *     {
     *         "error": "PaymentFailed"
     *     }
     */
    public function actionReserve($id){
        $om = OrderManager::getInstance($id);
        $om->checkIsTicketsIsset();

        $payment = PaymentService::getInstance();

        $payment->pay($om, PaymentService::TYPE_RESERVE)
            ->afterPayment($om, Ticket::STATUS_RESERVE);

        return $om->getModel();
    }

    /**
     * @api {get} /orders/:id/buy Buy
     * @apiName actionBuy
     * @apiGroup Order
     *
     * @apiParam {Number} id  Order ID.
     *
     * @apiSuccess {Number} id Order unique ID.
     * @apiSuccess {Number} client_id  Client ID.
     * @apiSuccess {String} status  Order status .
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "id": "1",
     *         "client_id": "1",
     *         "status": "open",
     *     }
     *
     * @apiError OrderClosed  Order by this order_id is closed.
     * @apiError OrderNotFound  Order by this order_id not found.
     * @apiError OrderBelongsToAnotherClient  This order belongs to another client.
     * @apiError OrderWithoutTickets  Order without tickets.
     * @apiError PaymentFailed  Payment failed.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 400 Bad Request
     *     {
     *         "error": "OrderClosed"
     *     }
     *or
     *     HTTP/1.1 402 Payment Required
     *     {
     *         "error": "PaymentFailed"
     *     }
     */
    public function actionBuy($id){
        $om = OrderManager::getInstance($id);
        $om->checkIsTicketsIsset();

        $payment = PaymentService::getInstance();

        $payment->pay($om, PaymentService::TYPE_BUY)
            ->afterPayment($om, Ticket::STATUS_CLOSE);

        return $om->getModel();
    }

    protected function verbs()
    {
        return ArrayHelper::merge(parent::verbs(), [
            'buy' => ['GET'],
            'reserve' => ['GET'],
        ]);
    }

}
