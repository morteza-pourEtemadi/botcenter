<?php

namespace common\models\botId_16;

use Yii;

/**
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $attachments
 * @property integer $create_time
 *
 */
class Shows extends \common\components\RedisActiveRecord
{
    public function attributes()
    {
        return [
            'id',
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
            [['id', 'create_time'], 'integer'],
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

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
