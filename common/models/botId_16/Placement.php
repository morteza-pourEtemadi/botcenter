<?php

namespace common\models\botId_16;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\components\RedisActiveRecord;

/**
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $attachments
 * @property integer $create_time
 * @property integer $category
 * @property integer $subcategory
 * @property integer $status
 * @property integer $creator_id
 *
 */
class Placement extends RedisActiveRecord
{
    const STATUS_PENDING = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_DENIED = 2;
    const STATUS_SUBMITTING = 3;
    const STATUS_WRITING = 4;

    public function attributes()
    {
        return [
            'id',
            'title',
            'text',
            'attachments',
            'category',
            'subcategory',
            'status',
            'create_time',
            'creator_id',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'create_time', 'creator_id', 'category', 'subcategory', 'status'], 'integer'],
            [['title', 'text', 'attachments'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'text' => Yii::t('app', 'Text'),
            'attachments' => Yii::t('app', 'Attachments'),
            'create_time' => Yii::t('app', 'Create Time'),
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    RedisActiveRecord::EVENT_BEFORE_INSERT => 'status',
                ],
                'value' => self::STATUS_PENDING
            ],
        ];
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
