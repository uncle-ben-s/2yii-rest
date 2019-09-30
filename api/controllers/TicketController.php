<?php
namespace api\controllers;

use api\services\TicketManager;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;

/**
 * Ticket controller
 */
class TicketController extends ActiveController
{
    public $modelClass = 'common\models\Ticket';

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
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                ],
            ]
        ]);
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    /**
     * @api {get} /tickets Tickets list
     * @apiName actionIndex
     * @apiGroup Ticket
     *
     *
     * @apiSuccess {Ticket[]} tickets       Tickets list.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     *         {
     *             "id": "1",
     *             "column": "1",
     *             "row": "1",
     *             "amount": "58",
     *             "status": "open",
     *             "expired_at": "1569527422",
     *             "block_expired_at": "1569016369",
     *             "orderTicket": null,
     *             "order": null
     *         },
     *         ...
     *     ]
     * or
     *     HTTP/1.1 200 OK
     *     []
     *
     */

    public function actionIndex(){
        return TicketManager::getAll();
    }
}
