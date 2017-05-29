<?php

namespace common\models\bot;

use yii\redis\ActiveQuery;
use yii\behaviors\TimestampBehavior;
use common\components\RedisActiveRecord;

/**
 * Class Subscribers
 * @package common\models\bot
 *
 * @property integer id
 * @property integer user_id
 * @property integer bot_id
 * @property integer status
 * @property string authority
 * @property integer price
 * @property integer time
 * @property string product
 * @property string description
 * @property string redirect_url
 * @property integer created_at
 * @property integer updated_at
 */
class Receipt extends RedisActiveRecord
{
    const STATUS_PAYED_ACTIVE = 1;
    const STATUS_PAYED_USED = 2;
    const STATUS_NOT_PAYED = 3;

    /**
     * @return array the list of attributes for this record
     */
    public function attributes()
    {
        return [
            'id',
            'user_id',
            'bot_id',
            'status',
            'price',
            'time',
            'product',
            'authority',
            'description',
            'redirect_url',
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
            [['id', 'user_id', 'bot_id', 'status', 'price','time', 'created_at', 'updated_at'], 'integer'],
            [['product', 'authority', 'description', 'redirect_url'], 'string'],
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
     * Defines a scope that modifies the `$query` to return only active(status = 1) customers
     * @param ActiveQuery $query
     */
    public static function active($query)
    {
        $query->andWhere(['status' => 1]);
    }

    /**
     * @inheritdoc
     * @return ReceiptQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReceiptQuery(get_called_class());
    }
}
