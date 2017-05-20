<?php

namespace common\components;

use yii\rbac\Rule;

class OwnerRule extends Rule
{
    public $name = 'isOwner';

    public function execute($user, $item, $params)
    {
        return isset($params['post']) ? $params['post']->creator_id == $user : false;
    }
}