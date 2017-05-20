<?php

namespace common\models\bot;

use yii\helpers\Json;
use yii\behaviors\TimestampBehavior;
use common\components\RedisActiveRecord;

/**
 * Class Users
 * @package common\models\bot
 *
 * @property integer id
 * @property integer user_id
 * @property string first_name
 * @property string last_name
 * @property string username
 * @property string phone
 * @property string email
 * @property string settings
 * @property string old_data
 * @property integer created_at
 * @property integer updated_at
 */
class Users extends RedisActiveRecord
{

    /**
     * @return array the list of attributes for this record
     */
    public function attributes()
    {
        return [
            'id',
            'user_id',
            'first_name',
            'last_name',
            'username',
            'phone',
            'email',
            'settings',
            'old_data',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['first_name', 'last_name', 'username', 'phone', 'email', 'settings', 'old_data'], 'string'],
            [['old_data', 'settings'], 'default', 'value' => '[]'],
            [['phone', 'email'], 'default', 'value' => ''],
        ];
    }

    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     * @return UsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UsersQuery(get_called_class());
    }

    /**
     * Add a (new) value to json string of settings
     * @param $key
     * @param $value
     * @return bool
     */
    public function setSettings($key, $value)
    {
        $settings = Json::decode($this->settings);
        $settings[$key] = $value;
        $this->settings = Json::encode($settings);
        return $this->save();
    }
}
