<?php

namespace common\components;

use yii\rbac\Rule;
use common\models\User;

class DepartmentRule extends Rule
{
    public $name = 'isThisDepartment';

    public function execute($user, $item, $params)
    {
        $role = User::findOne(['id' => $user]);
        if ($role->role == User::ROLE_MASTER) {
            return true;
        }
        return isset($params['post']) ? $params['post']->department_id == $role->department : false;
    }
}