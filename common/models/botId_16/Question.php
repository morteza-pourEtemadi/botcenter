<?php

namespace common\models\botId_16;

use Yii;

/**
 * This is the model class for User.
 *
 * @property integer $id
 * @property integer $index
 * @property string $text
 * @property string $options
 * @property integer $type
 *
 */
class Question extends \common\components\RedisActiveRecord
{
    const TYPE_EXHIBITOR = 0;
    const TYPE_PARTICIPANT = 1;

    public function attributes()
    {
        return [
            'id',
            'index',
            'text',
            'options',
            'type',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['index', 'type'], 'integer'],
            [['text', 'options'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
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
