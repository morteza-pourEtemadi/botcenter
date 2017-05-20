<?php

namespace common\components;

use yii\rbac\Rule;
use common\models\User;

class ProfileRule extends Rule
{
    public $name = 'canUserViewProfile';

    public function execute($user, $item, $params)
    {
        // @TODO: only experts that accepted an order can be viewed
        return false;
    }
}