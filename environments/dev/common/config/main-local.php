<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=ultimatedevelopers',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'tablePrefix' => 'ud_',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'api/hook/<tokenId:[\d]+>:<tokenString:[^\/]+>' => 'api/hook',
                'send/massive/<tokenId:[\d]+>:<tokenString:[^\/]+>' => 'send/massive',
            ],
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
            'password' => '76b8a9424ba91fdaed187ef7d5feb88c1f46a8ef12babe7ee23fed708ba90912ab8412cc9d9b668bb1ac9743be69b3974fdbdca83d6022b28fe5ccc7eadfd25d',
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        'session' => [
            'class' => 'yii\redis\Session',
        ],
        'zarinpal' => [
            'class' => 'amirasaran\zarinpal\Zarinpal',
            'merchant_id' => '54637a2a-4214-11e7-8d8b-005056a205be',
            'callback_url' => 'http://ultimatedevelopers.loc/payment/bots'
        ],
    ],
];
