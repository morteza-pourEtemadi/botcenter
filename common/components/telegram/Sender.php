<?php

namespace common\components\telegram;

use Yii;
use CURLFile;
use yii\base\Model;
use yii\base\Exception;
use common\components\TelegramBot;

/**
 * Class TelegramSender
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class Sender extends Model
{
    public $text;
    public $file;
    public $bot;
    public $media_type;
    public $forward_chat_id;
    public $forward_message_id;
    public $reply_to_message_id;
    public $reply_markup;
    public $parse_mode = 'HTML';
    public $response;

    private $_telegram_bot;
    private $_params;

    /**
     * Returns telegram method names for each media type in an array
     * @return array
     */
    protected static function getActions()
    {
        return [
            1 => 'sendMessage',
            2 => 'sendPhoto',
            3 => 'sendVideo',
            4 => 'sendAudio',
            5 => 'sendDocument',
            6 => 'forwardMessage',
            7 => 'sendDocument',
        ];
    }

    /**
     * Returns method name specific for media type
     * @return mixed
     */
    protected function getAction()
    {
        return static::getActions()[$this->media_type];
    }

    /**
     * Return action params
     * @return mixed
     */
    protected function getParams()
    {
        if ($this->_params === null || $this->fileChanged()) {
            $params = [
                1 => [$this->text, $this->reply_to_message_id, $this->reply_markup, $this->parse_mode],
                2 => [$this->file, $this->text, $this->reply_to_message_id, $this->reply_markup],
                3 => [$this->file, $this->text, $this->reply_to_message_id, $this->reply_markup],
                4 => [$this->file, $this->reply_to_message_id, $this->reply_markup],
                5 => [$this->file, $this->text, $this->reply_to_message_id, $this->reply_markup],
                6 => [$this->forward_chat_id, $this->forward_message_id],
                7 => [$this->file, $this->text, $this->reply_to_message_id, $this->reply_markup],
            ];

            return $this->_params = $params[$this->media_type];
        }

        return $this->_params;
    }

    /**
     * Return file changed or not
     * @return bool
     */
    protected function fileChanged()
    {
        return $this->file && $this->_params && $this->_params[0] !== $this->file;
    }

    /**
     * Returns instance of telegram bot class
     * @return TelegramBot
     */
    protected function getTelegramBot()
    {
        if ($this->_telegram_bot === null) {
            return $this->_telegram_bot = new TelegramBot(['authKey' => $this->bot['token']]);
        }

        return $this->_telegram_bot;
    }

    /**
     * Send anything to the telegram chats or channels
     * @param integer $to destination chat_id
     * @return bool
     */
    public function send($to)
    {
        try {
            $response = call_user_func_array([$this->telegramBot, $this->action], array_merge([$to], $this->params));
        } catch (Exception $e) {
            Yii::error(['message' => $e->getMessage(), 'file' => $e->getFile().':'.$e->getLine()], __METHOD__);
            return false;
        }

        if (isset($response->ok) === false) {
            return false;
        }

        if ($response->ok) {
            // @todo: Add more data for audio
            if ($this->media_type === 4 && $this->text) {
                $this->telegramBot->sendMessage($to, $this->text, $response->result->message_id);
            }

            if ($this->file && $this->file instanceof CURLFile) {
                $type = strtolower(ltrim($this->action, 'send'));
                if (isset($response->result->$type)) {
                    $file = $response->result->$type;
                    $this->file = is_array($file) ? end($file)->file_id : $file->file_id;
                }
            }

            return true;
        }

        $this->response = $response;
        return false;
    }

    /**
     * Send content to the telegram chat and handle channel_member status
     * @param $chat
     * @return bool
     * @throws \Exception
     */
    public function sendToChat($chat) {
        $chat_id = $chat->chat_id;
        if ($this->send($chat_id)) {
            return true;
        }

        return false;
    }

    /**
     * Send content to the telegram channel
     * @param $channelUsername
     * @return bool
     * @throws \Exception
     */
    public function sendToChannel($channelUsername)
    {
        if ($this->send('@'.$channelUsername)) {
            return true;
        }

        if ($this->response === null) {
            return false;
        }

        switch ($this->response->description) {
            case '[Error]: Too many requests: retry later':
                sleep(60);
                break;
            case '[Error]: Bad Request: channel not found':
                //break;
            default:
                Yii::warning([
                    'message' => "[Telegram] Unknown Response on `{$this->action}` with chat_id '$channelUsername'",
                    'response' => $this->response
                ], __METHOD__);
                break;
        }
        return false;
    }
}
