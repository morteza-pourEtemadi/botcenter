<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=ultimatedevelopers',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
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
            'merchant_id' => 'e6532f90-9e7a-11e6-b311-000c295eb8fc',
            'callback_url' => 'http://redis.botcenterbot.loc/payment/bots'
        ],
    ],
];
