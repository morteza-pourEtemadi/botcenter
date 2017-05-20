<?php
return [
    'name' => 'Ultimate Developer',
    'language' => 'fa-IR',
    'timeZone' => 'Asia/Tehran',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\GettextMessageSource',
                    'basePath' => '@common/messages',
                ],
            ],
        ],
    ],
];
