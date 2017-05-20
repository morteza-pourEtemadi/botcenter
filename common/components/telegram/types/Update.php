<?php
/**
 * @link http://www.noghteh.ir/
 * @copyright Copyright (c) 2015 Noghteh
 * @license http://www.noghteh.ir/license/
 */

namespace common\components\telegram\types;

/**
 * Update
 * @property int $update_id
 * @property Message $message
 * @property Message $edited_message
 * @property Message $baseMessage
 * @property CallbackQuery $callback_query
 */
class Update extends BaseType
{
    public $update_id;
    public $message;
    public $edited_message;
    public $callback_query;

    /**
     * @inheritdoc
     */
    public function objectMap()
    {
        return [
            'callback_query' => CallbackQuery::className(),
            'message' => Message::className(),
            'edited_message' => Message::className(),
        ];
    }

    /**
     * Return original message or edited message object
     * @return Message
     */
    public function getBaseMessage()
    {
        return $this->isEditedMessage() ? $this->edited_message : $this->message;
    }

    /**
     * Is edited message
     * @return bool
     */
    public function isEditedMessage()
    {
        return $this->edited_message !== null;
    }
}
