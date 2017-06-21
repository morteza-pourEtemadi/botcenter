<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=bots',
            'username' => 'root',
            'password' => 'SDKmtz137261!@#',
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
            'password' => '0fb935ce0e5f972cb03cba20694c667ffff0179bcdf03c056dcca837c87370c14c87e3e94e851b74b5bf7a28fd8101da9a4be2ba32ebd13d45ed92fbd6398d30',
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
            'callback_url' => 'https://ultimatedevelopers.ir/payment/bots'
        ],
    ],
];
