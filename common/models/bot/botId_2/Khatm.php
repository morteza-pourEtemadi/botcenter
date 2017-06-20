<?php

namespace common\models\bot\botId_2;

use Yii;
use common\components\RedisActiveRecord;

/**
 * Class Khatm
 * @package common\models\botId_2
 *
 * @property integer id
 * @property integer number
 * @property string title
 * @property integer type
 * @property integer status
 * @property integer current_pointer
 * @property integer created_at
 */
class Khatm extends RedisActiveRecord
{
    const TYPE_AYA = 1;
    const TYPE_PAGE = 2;
    const TYPE_JOZ = 3;

    const STATUS_ACTIVE = 1;
    const STATUS_FINISHED = 2;
    const STATUS_ABORTED = 3;

    public function attributes()
    {
        return [
            'id',
            'number',
            'current_pointer',
            'title',
            'type',
            'status',
            'created_at'
        ];
    }

    public function rules()
    {
        return [
            [['id', 'number', 'current_pointer', 'type', 'created_at', 'status'], 'integer'],
            [['title'], 'string'],
            ['type', 'default', 'value' => self::TYPE_AYA],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
        ];
    }

    public function getType()
    {
        $types = [
            self::TYPE_AYA => Yii::t('app_2', 'type aya'),
            self::TYPE_PAGE => Yii::t('app_2', 'type page'),
            self::TYPE_JOZ => Yii::t('app_2', 'type joz'),
        ];

        return $types[$this->type];
    }

    public function getTypePart()
    {
        $types = [
            self::TYPE_AYA => Yii::t('app_2', 'aya'),
            self::TYPE_PAGE => Yii::t('app_2', 'page'),
            self::TYPE_JOZ => Yii::t('app_2', 'joz'),
        ];

        return $types[$this->type];
    }
}
