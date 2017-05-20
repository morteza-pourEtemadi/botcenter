<?php

namespace common\models\bot;

use yii\redis\ActiveQuery;
use yii\behaviors\AttributeBehavior;
use common\components\RedisActiveRecord;

/**
 * Class Bot
 * @package common\models\bot
 *
 * @property integer id
 * @property integer bot_id
 * @property integer telegram_id
 * @property string first_name
 * @property string username
 * @property string token
 * @property string priceString
 * @property integer type
 * @property string translations
 */
class Bot extends RedisActiveRecord
{
    public $price = 0;

    const TYPE_IN_APP_PAYMENT = 1;
    const TYPE_SUBSCRIPTION = 2;

    public function attributes()
    {
        return [
            'id',
            'bot_id',
            'telegram_id',
            'first_name',
            'username',
            'token',
            'type',
            'priceString',
            'translations'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'bot_id', 'telegram_id', 'type'], 'integer'],
            [['first_name', 'username', 'token', 'priceString', 'translations'], 'string'],
            ['priceString', 'default', 'value' => '[]'],
        ];
    }

    /**
     * @inheritdoc
     * @return BotQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BotQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getSubscribers()
    {
        return $this->hasMany(Subscribers::className(), ['bot_id' => 'bot_id']);
    }
}
