<?php
namespace common\components\telegram\types;

use common\components\telegram\types\keyboards\KeyboardButton;
use common\components\telegram\types\keyboards\InlineKeyboardButton;

/**
 * Update
 * @property string $url
 * @property string $text
 * @property string $file_id
 * @property string $caption
 * @property string $from_chat_id
 * @property integer $message_id
 * @property User[] $users
 * @property InlineKeyboardButton[] $inline
 * @property KeyboardButton[] $keys
 * @property integer[] sort
 */
class Sender extends BaseType
{
    public $url;
    public $text;
    public $file_id;
    public $caption;
    public $from_chat_id;
    public $message_id;
    public $users;
    public $keys;
    public $inline;
    public $sort;

    /**
     * @inheritdoc
     */
    public function objectMap()
    {
        return [
            'users' => User::className(),
            'keys' => KeyboardButton::className(),
            'inline' => InlineKeyboardButton::className(),
        ];
    }

    public function arrayObjects()
    {
        return [
            'users',
            'keys',
            'inline'
        ];
    }
}
