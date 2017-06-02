<?php

namespace common\models\bot;

use yii\redis\ActiveQuery;
use common\components\TelegramBot;
use yii\behaviors\TimestampBehavior;
use common\components\RedisActiveRecord;

/**
 * Class Subscribers
 * @package common\models\bot
 *
 * @property integer id
 * @property integer user_id
 * @property integer bot_id
 * @property string memberString
 * @property integer status
 * @property integer created_at
 * @property integer updated_at
 *
 * @property Users user
 */
class Subscribers extends RedisActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 2;

    /**
     * @return array the list of attributes for this record
     */
    public function attributes()
    {
        return [
            'id',
            'user_id',
            'bot_id',
            'memberString',
            'status',
            'created_at',
            'updated_at'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'bot_id', 'status', 'created_at', 'updated_at'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['memberString'], 'default', 'value' => '[]'],
            [['memberString'], 'string'],
        ];
    }

    public function beforeSave($insert)
    {
        $bot = Bot::findOne(['bot_id' => $this->bot_id]);
        $api = new TelegramBot(['authKey' => $bot->token]);
        $chat = $api->getChat($this->user_id);

        if ($oldUser = Users::findOne(['user_id' => $this->user_id])) {
            $user = $oldUser;
        } else {
            $user = new Users();
        }

        $user->user_id = $this->user_id;
        $user->first_name = isset($chat->first_name) ? $chat->first_name : '';
        $user->last_name = isset($chat->last_name) ? $chat->last_name : '';
        $user->username = isset($chat->username) ? $chat->username : '';
        $user->save();

        return parent::beforeSave($insert);
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
     * Defines a scope that modifies the `$query` to return only active(status = 1) customers
     * @param ActiveQuery $query
     */
    public static function active($query)
    {
        $query->andWhere(['status' => 1]);
    }

    public static function blockedChat($chatId, $botId)
    {
        $blocker = Subscribers::findOne(['user_id' => $chatId, 'bot_id' => $botId]);
        if ($blocker) {
            $blocker->status = self::STATUS_BLOCKED;
            $blocker->save();
        }
        return true;
    }

    public static function unblockedChat($chatId, $botId)
    {
        $user = Subscribers::findOne(['user_id' => $chatId, 'bot_id' => $botId]);
        if ($user) {
            $user->status = self::STATUS_ACTIVE;
            $user->save();
        }
        return true;
    }

    /**
     * @return Users
     */
    public function getUser()
    {
        return Users::findOne(['user_id' => $this->user_id]);
    }

    /**
     * @inheritdoc
     * @return SubscribersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SubscribersQuery(get_called_class());
    }
}
