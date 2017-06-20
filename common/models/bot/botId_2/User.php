<?php

namespace common\models\bot\botId_2;

use yii\helpers\Json;
use common\models\bot\Users;
use common\components\RedisActiveRecord;

/**
 * Class User
 * @package common\models\botId_1
 *
 * @property integer id
 * @property integer user_id
 * @property string current_aya
 * @property integer type
 * @property integer created_at
 *
 * @property Users $uniqueUser
 */
class User extends RedisActiveRecord
{
    const TYPE_NORMAL = 1;
    const TYPE_ADMIN = 2;
    const TYPE_OWNER = 3;

    public function attributes()
    {
        return [
            'id',
            'user_id',
            'current_aya',
            'type',
            'created_at',
        ];
    }

    public function rules()
    {
        return [
            [['id', 'user_id', 'type', 'created_at'], 'integer'],
            [['current_aya'], 'string'],
            ['current_aya', 'default', 'value' => Json::encode([])],
            ['type', 'default', 'value' => self::TYPE_NORMAL],
            ['created_at', 'default', 'value' => time()],
        ];
    }

    /**
     * @return Users
     */
    public function getUniqueUser()
    {
        return Users::findOne(['user_id' => $this->user_id]);
    }
}
