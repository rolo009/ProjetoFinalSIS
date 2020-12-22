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
                        'GET tipomonumento/{tipo}' => 'tipomonumento',
                        'GET estiloconstrucao/{estilo}' => 'estiloconstrucao',
                        'GET estatisticas/{id}' => 'estatisticas',
                        'GET localidade/{local}' => 'localidade', //não reconhece
                        'GET pontoturisticodetails/{id}' => 'pontoturistico', //não reconhece
                    ],
                    'tokens' => [
                            '{id}' => '<id:\d+>',
                            '{local}' => '<local:.*?>',
                            '{estilo}' => '<estilo:.*?>',
                            '{tipo}' => '<tipo:.*?>',
],
                ],

                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'userprofile',
                    'pluralize' => false,
                    'except' => ['delete', 'create'],
                    'extraPatterns' => [
                        'GET favoritos/{id}' => 'favoritos',
                        'GET info/{id}' => 'info', //recolhe info da tabela user e userprofile
                        'PUT baniruser/{id}' => 'baniruser',
                    ],
                ],

                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'contactos',
                    'pluralize' => false,
                    'except' => ['delete', 'create'],
                    'extraPatterns' => [
                        'GET mensagem/{id}' => 'mensagem',
                        'GET naolidas' => 'naolidas',
                        'GET lidas' => 'lidas'
                    ],
                ]
            ],

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
