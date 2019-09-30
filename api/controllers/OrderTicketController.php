<?php

namespace api\controllers;

use api\services\OrderManager;
use api\services\OrderTicketManager;
use api\services\TicketManager;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\web\ServerErrorHttpException;

class OrderTicketController extends ActiveController
{
    public $modelClass = 'common\models\OrderTicket';

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
                'rules' => [
                    /*[
                        'allow' => false,
                        'actions' => [],
                        'roles' => ['@'],
                    ],*/
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ]
        ]);
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['view'], $actions['create'], $actions['delete']);
        return $actions;
    }


    /**
     * @api {get} /order-tickets Elements list
     * @apiName actionIndex
     * @apiGroup OrderTicket
     *
     *
     * @apiSuccess {OrderTicket[]} array   List of order-tickets.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     *          {
     *              "id": "5",
     *              "order_id": "2",
     *              "ticket_id": "2",
     *          },
     *          ...
     *     ]
     *
     * or
     *     HTTP/1.1 200 OK
     *     []
     */
//    public function actionIndex(){}

    /**
     * @api {get} /order-tickets/:id  Details
     * @apiName actionView
     * @apiGroup OrderTicket
     *
     * @apiParam {Number} id OrderTicket unique ID.
     *
     * @apiSuccess {Number} id OrderTicket unique ID.
     * @apiSuccess {Number} order_id  Order ID.
     * @apiSuccess {Number} ticket_id  Ticket ID.
     *
     * @apiSuccessExample Success-Response:

     *     HTTP/1.1 200 OK
     *     {
     *         "id": "5",
     *         "order_id": "2",
     *         "ticket_id": "2",
     *     }
     *
     * @apiError OrderTicketNotFound Bad order ticket identifier.
     * @apiError OrderTicketBelongsToAnotherClient This order ticket belongs to another client.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 400 Bad Request
     *     {
     *         "error": "OrderTicketNotFound"
     *     }
     *
     */
    public function actionView($id){
        return OrderTicketManager::getInstance($id)->getModel();
    }


    /**
     * @api {post} /order-tickets Create
     * @apiName actionCreate
     * @apiGroup OrderTicket
     *
     * @apiParam {Number} order_id  Order ID.
     * @apiParam {Number} ticket_id  Ticket ID.
     *
     * @apiSuccess {Number} id OrderTicket unique ID.
     * @apiSuccess {Number} order_id  Order ID.
     * @apiSuccess {Number} ticket_id  Ticket ID.
     *
     * @apiSuccessExample Success-Response:
     *
     *     HTTP/1.1 200 OK
     *     {
     *         "id": "5",
     *         "order_id": "2",
     *         "ticket_id": "2",
     *     }
     *
     * @apiError OrderClosed  Order by this order_id is closed.
     * @apiError OrderNotFound  Order by this order_id not found.
     * @apiError OrderBelongsToAnotherClient  This order belongs to another client.
     * @apiError TicketNotFound  Not found ticket by ticket_id.
     * @apiError TicketNotAvailable  This ticket is not available.
     * @apiError TicketReserved  This ticket is reserved.
     * @apiError TicketExpired  This ticket has expired.
     * @apiError TicketBlockedByAnotherClient  This ticket has blocked by another client.
     * @apiError OrderTicketBelongsToAnotherClient This order ticket belongs to another client.
     * @apiError OrderTicketDataValidationFailed  Data are not valid.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 400 Bad Request
     *     {
     *         "error": "OrderClosed"
     *     }
     * or
     *     HTTP/1.1 400 Bad Request
     *     {
     *         "error": "OrderTicketDataValidationFailed"
     *         "description": "Order ID is invalid."
     *     }
     *
     */
    public function actionCreate()
    {
        OrderTicketManager::validateModelData(Yii::$app->request->bodyParams);

        $ticketId = Yii::$app->request->getBodyParam('ticket_id');
        $orderId = Yii::$app->request->getBodyParam('order_id');

        OrderManager::getInstance($orderId); // check order model

        $ts = TicketManager::getInstance($ticketId);

        $otm = ($ts->isBlocked()) ? OrderTicketManager::getInstance($ts->getModel()->orderTicket->id)
            : OrderTicketManager::getCreatedInstance($orderId, $ticketId);

        $ts->setExpiredAt(time());

        Yii::$app->getResponse()->setStatusCode(200);

        return $otm->getModel();
    }

    /**
     * @api {delete} /order-tickets/:id Delete
     * @apiName actionDelete
     * @apiGroup OrderTicket
     *
     * @apiParam {Number} id OrderTicket unique ID.
     *
     * @apiSuccess {Number} id OrderTicket unique ID.
     * @apiSuccess {Number} order_id  Order ID.
     * @apiSuccess {Number} ticket_id  Ticket ID.
     *
     * @apiSuccessExample Success-Response:
     *
     *     HTTP/1.1 200 OK
     *     {
     *         "id": "5",
     *         "order_id": "2",
     *         "ticket_id": "2",
     *     }
     *
     * @apiError OrderTicketNotFound Bad order ticket identifier.
     * @apiError OrderTicketBelongsToAnotherClient This order ticket belongs to another client.
     * @apiError TicketNotFound  Not found ticket by ticket_id.
     * @apiError TicketNotAvailable  This ticket is not available.
     * @apiError TicketReserved  This ticket is reserved.
     * @apiError TicketExpired  This ticket has expired.
     * @apiError TicketBlockedByAnotherClient  This ticket has blocked by another client.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 400 Bad Request
     *     {
     *         "error": "OrderTicketNotFound"
     *     }
     *
     */
    public function actionDelete($id){
        $om = OrderTicketManager::getInstance($id);
        $model = $om->getModel();

        $tm = TicketManager::getInstance($model->ticket_id);

        if (!is_null($model) && ($model->delete() === false)) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(200);

        $tm->unsetExpiredAt();

        return $model;
    }

    protected function verbs()
    {
        return ArrayHelper::merge(parent::verbs(), [
            'delete' => ['DELETE'],
        ]);
    }
}
