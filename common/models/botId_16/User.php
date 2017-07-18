<?php

namespace common\models\botId_16;

use Yii;
use common\models\Users;

/**
 * This is the model class for User.
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property integer $status
 * @property string $survey
 * @property integer $create_time
 *
 * @property Users $uniqueUser
 */
class User extends \common\components\RedisActiveRecord
{
    const TYPE_EXHIBITOR = 0;
    const TYPE_PARTICIPANT = 1;

    const STATUS_ACTIVE = 1;

    public function attributes()
    {
        return [
            'id',
            'user_id',
            'type',
            'status',
            'survey',
            'create_time',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'create_time', 'type', 'status'], 'integer'],
            [['survey'], 'string'],
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
     * @return Users
     */
    public function getUniqueUser()
    {
        return Users::findOne(['user_id' => $this->user_id]);
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
