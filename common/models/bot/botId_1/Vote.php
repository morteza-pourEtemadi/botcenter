<?php

namespace common\models\bot\botId_1;

use yii\behaviors\TimestampBehavior;
use common\components\RedisActiveRecord;

/**
 * Class Vote
 * @package common\models\botId_1
 *
 * @property integer id
 * @property integer voter
 * @property integer item
 * @property integer type
 * @property integer status
 * @property integer created_at
 * @property integer updated_at
 *
 */
class Vote extends RedisActiveRecord
{
    const TYPE_LOVE = 1;
    const TYPE_LIKE = 2;
    const TYPE_DISLIKE = 3;
    const TYPE_HATE = 4;
    const TYPE_REPORT = 5;

    const STATUS_NORMAL = 1;
    const STATUS_COMPLAINED = 2;

    public function attributes()
    {
        return [
            'id',
            'voter',
            'item',
            'type',
            'status',
            'created_at',
            'updated_at'
        ];
    }

    public function rules()
    {
        return [
            [['voter', 'item', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_NORMAL],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    RedisActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    RedisActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
}
