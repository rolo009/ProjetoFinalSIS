<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'hM0mvbrrLR-3MGxUnzejLZ-vJVZf-ZDV',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'pontosturisticos',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET search/{pesquisa}' => 'search',
                        'GET tipomonumento/{tipo}' => 'tipomonumento',
                        'GET estatisticas/{id}' => 'estatisticas',
                        'GET localidade/{local}' => 'localidade', //não reconhece
                        'GET info' => 'info', //não reconhece
                    ],
                    'tokens' => [
                        '{id}' => '<id:\d+>',
                        '{local}' => '<local:.*?>',
                        '{tipo}' => '<tipo:.*?>',
                        '{pesquisa}' => '<pesquisa:.*?>',
                    ],
                ],

                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'userprofile',
                    'pluralize' => false,
                    'except' => ['delete', 'create', 'update', 'view'],
                    'extraPatterns' => [
                        'GET info/{id}' => 'info',
                        'PATCH apagaruser/{token}' => 'apagaruser',
                        'POST registo' => 'registo',
                        'GET username/{user}' => 'username',
                        'PUT editar/{token}' => 'editar',
                        'POST login' => 'login',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\d+>',
                        '{user}' => '<user:.*?>',
                        '{token}' => '<token:.*?>',
                    ],
                ],

                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'contactos',
                    'pluralize' => false,
                    'except' => ['delete', 'update'],
                    'extraPatterns' => [
                        'GET mensagem/{id}' => 'mensagem',
                        'GET naolidas' => 'naolidas',
                        'GET lidas' => 'lidas',
                        'POST registo' => 'registo'
                    ],
                ],
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'favoritos',
                    'pluralize' => false,
                    'except' => ['update'],
                    'extraPatterns' => [
                        'GET info/{token}' => 'info',
                        'POST add' => 'add',
                        'DELETE remover/{id}/{token}' => 'remover',
                        'GET check/{id}/{token}' => 'check',
                    ],
                    'tokens' => [
                        '{token}' => '<token:.*?>',
                        '{id}' => '<id:\d+>',
                    ],
                ],

                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'estiloconstrucao',
                    'pluralize'=>false,
                ],
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'tipomonumento',
                    'pluralize'=>false,
                ],
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'localidade',
                    'pluralize'=>false,
                ],
            ]

        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
