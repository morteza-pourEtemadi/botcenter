<?php

namespace common\models\bot\botId_1;

use common\models\bot\Users;
use common\components\RedisActiveRecord;

/**
 * Class User
 * @package common\models\botId_1
 *
 * @property integer id
 * @property integer user_id
 * @property integer type
 * @property string seenXs
 * @property integer coins
 * @property integer XsNo
 * @property integer Xs_loves
 * @property integer Xs_likes
 * @property integer Xs_dislikes
 * @property integer Xs_hates
 * @property integer bonus_score
 *
 * @property Users $uniqueUser
 */
class User extends RedisActiveRecord
{
    const TYPE_NORMAL = 1;
    const TYPE_PREMIUM = 2;

    public function attributes()
    {
        return [
            'id',
            'user_id',
            'type',
            'seenXs',
            'coins',
            'XsNo',
            'Xs_loves',
            'Xs_likes',
            'Xs_dislikes',
            'Xs_hates',
            'bonus_score',
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'type', 'XsNo', 'coins', 'Xs_loves', 'Xs_likes', 'Xs_dislikes', 'Xs_hates', 'bonus_score'], 'integer'],
            ['seenXs', 'string'],
            ['seenXs', 'default', 'value' => '[]'],
            ['type', 'default', 'value' => self::TYPE_NORMAL],
            [['coins', 'XsNo', 'Xs_loves', 'Xs_likes', 'Xs_dislikes', 'Xs_hates'], 'default', 'value' => 0],
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
