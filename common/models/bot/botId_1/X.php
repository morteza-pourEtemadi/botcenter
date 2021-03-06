<?php

namespace common\models\bot\botId_1;

use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use common\components\RedisActiveRecord;

/**
 * Class X
 * @package common\models\botId_1
 *
 * @property integer id
 * @property integer creator_id
 * @property string file_id
 * @property string caption
 * @property string code
 * @property string specialOptions
 * @property integer status
 * @property integer created_at
 *
 */
class X extends RedisActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_REPORTED = 2;

    public function attributes()
    {
        return [
            'id',
            'creator_id',
            'file_id',
            'caption',
            'code',
            'specialOptions',
            'status',
            'created_at',
        ];
    }

    public function rules()
    {
        return [
            [['creator_id', 'status', 'created_at'], 'integer'],
            [['specialOptions', 'caption', 'file_id', 'code'], 'string'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    RedisActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ]
        ];
    }

    public function setCode()
    {
        $this->code = base64_encode('X:' . $this->id . ':' . $this->creator_id);
        return $this->save();
    }
}
