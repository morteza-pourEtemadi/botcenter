<?php

namespace common\components;

use yii\rbac\Rule;
use common\models\User;

class UpdateProfileRule extends Rule
{
    public $name = 'canUserUpdateProfile';

    public function execute($user, $item, $params)
    {
        return isset($params['user_id']) ? ($params['user_id'] == $user) : false;
    }
}