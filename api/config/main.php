<?php
$params = array_merge(require __DIR__ . '/../../common/config/params.php', require __DIR__ . '/../../common/config/params-local.php', require __DIR__ . '/params.php', require __DIR__ . '/params-local.php');

return [
    'id'                  => 'app-api',
    'basePath'            => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap'           => ['log'],
    'modules'             => [],
    'components'          => [
        'request'  => [
            'parsers'                => [
                'application/json' => 'yii\web\JsonParser'
            ],
            'enableCookieValidation' => false,
        ],
        'response' => [
            'format'        => \yii\web\Response::FORMAT_JSON,
            'formatters'    => [
                \yii\web\Response::FORMAT_JSON => [
                    'class'         => 'yii\web\JsonResponseFormatter',
//                    'prettyPrint'   => YII_DEBUG,
//                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                ]
            ],
            'on beforeSend' => function($event){
                $response = $event->sender;
                if($response->data !== null){
                    $data = $response->data;
                    // Error handle
                    $error = '';
                    if(!$response->isSuccessful){
                        if(isset($data['message'])){
                            $error = $data['message'];
                        }elseif(isset(current($data)['message'])){
                            $error = current($data)['message'];
                        }

                        $response->data = [
//                        'status' => $response->isSuccessful,
//                        'code'   => $response->statusCode,
                            'error'  => $error,
                        ];

                        if(!empty($data['description'])){
                            $response->data['description'] = $data['description'];
                        }
                    }

                    if($response->isSuccessful){
                        $response->data = $data;
                    }
//                    $response->statusCode = 200;
                }
            },
        ],
        'user'     => [
            'identityClass'   => 'common\models\Client',
            'enableAutoLogin' => false,
            'enableSession'   => false,
            //            'identityCookie' => ['name' => '_identity-admin', 'httpOnly' => true],
        ],
        /*'session' => [
            // this is the name of the session cookie used for login on the admin
            'name' => 'advanced-admin',
        ],*/
        'log'      => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => [
                        'error',
                        'warning'
                    ],
                ],
            ],
        ],
        'errorHandler' => [
            'class' => 'api\base\ErrorHandler',
        ],

        'urlManager' => [
            'enablePrettyUrl'     => true,
            'enableStrictParsing' => false,
            'showScriptName'      => false,
            'rules'               => [
                'login' => 'site/login',
                ['class' => yii\rest\UrlRule::class, 'controller' => 'ticket'],
                ['class' => yii\rest\UrlRule::class, 'controller' => 'order-ticket'],
                ['class' => yii\rest\UrlRule::class, 'controller' => 'order'],
                'orders/<id>/reserve' => 'order/reserve',
                'orders/<id>/buy' => 'order/buy',
            ],
        ],

    ],
    'params'              => $params,
];
