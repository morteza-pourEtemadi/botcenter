<?php

namespace common\models\botId_16;

use Yii;

/**
 *
 * @property integer $id
 * @property integer $index
 * @property string $title
 * @property string $text
 * @property string $attachments
 * @property integer $create_time
 *
 */
class News extends \common\components\RedisActiveRecord
{
    public function attributes()
    {
        return [
            'id',
            'index',
            'title',
            'text',
            'attachments',
            'create_time',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'index', 'create_time'], 'integer'],
            [['title', 'text', 'attachments'], 'string'],
            ['title', 'default', 'value' => ''],
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

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
