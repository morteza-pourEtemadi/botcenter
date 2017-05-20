<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 't4PGEFjdOEUYd3mCRtCcDp203OpCKR1y',
            'parsers' => ['application/json' => 'yii\web\JsonParser'],
        ],
    ],
];
