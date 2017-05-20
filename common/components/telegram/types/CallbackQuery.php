<?php

namespace common\components\telegram\types;

/**
 * CallbackQuery
 * @property string $id
 * @property User $from
 * @property Message $message
 * @property string $inline_message_id
 * @property string $data
 */
class CallbackQuery extends BaseType
{
    public $id;
    public $from;
    public $message;
    public $inline_message_id;
    public $data;

    /**
     * @inheritdoc
     */
    public function objectMap()
    {
        return [
            'from' => User::className(),
            'message' => Message::className(),
        ];
    }
}
