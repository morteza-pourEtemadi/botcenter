<?php

namespace common\components;

use yii;
use yii\base\Component;
use yii\base\InvalidParamException;
use common\components\telegram\types\Chat;

/**
 * Telegram Bot
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 * @since 2.0
 */
class TelegramBot extends Component
{
    const API_URL = 'https://api.telegram.org/bot';

    /**
     * API endpoint for files
     */
    const API_FILE_URL = 'https://api.telegram.org/file/bot';

    public $authKey;

    protected $apiUrl;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (isset($this->authKey) === false) {
            throw new InvalidParamException('$authKey must be set.');
        }
        $this->apiUrl = self::API_URL . $this->authKey . '/';
    }

    /**
     * Returns recent messages
     * @param null $offset update_id for offset messages
     * @return mixed
     */
    public function getUpdates($offset = null)
    {
        return $this->get('getUpdates', ['offset' => $offset]);
    }

    /**
     * Returns bot information
     * @return mixed
     */
    public function getMe()
    {
        return $this->get('getMe');
    }

    /**
     * Returns base file download link
     * @return string
     */
    protected function getBaseFileUrl()
    {
        return self::API_FILE_URL . $this->authKey . '/';
    }

    /**
     * Set Webhook
     * @param $url
     * @return mixed
     */
    public function setWebhook($url)
    {
        return $this->post('setWebhook', ['url' => $url]);
    }

    /**
     * Get chat updates include updated name and username of the user
     * @param $chatId
     * @return Chat|Null
     */
    public function getChat($chatId)
    {
        $params = [
            'chat_id' => $chatId
        ];
        $response = $this->post('getChat', $params);
        if ($response->ok == true) {
            return $response->result;
        }
        return null;
    }

    public function getChatMember($chatId, $userId)
    {
        $params = [
            'chat_id' => $chatId,
            'user_id' => $userId
        ];
        return $this->post('getChatMember', $params);
    }

    /**
     * Edits a message reply markup
     * @param $chatId
     * @param $messageId
     * @param $inlineMessageId
     * @param $replyMarkup
     * @return mixed
     */
    public function editMessageReplyMarkup($chatId, $messageId, $replyMarkup, $inlineMessageId = null)
    {
        $params = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'reply_markup' => $replyMarkup ? json_encode($replyMarkup) : null,
            'inline_message_id' => $inlineMessageId,
        ];
        return $this->post('editMessageReplyMarkup', $params);
    }

    /**
     * @param $chatId
     * @param $messageId
     * @param $text
     * @param $replyMarkup
     * @param null $inlineMessageId
     * @return mixed
     */
    public function editMessageText($chatId, $messageId, $text, $replyMarkup, $inlineMessageId = null)
    {
        $params = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $text,
            'reply_markup' => $replyMarkup ? json_encode($replyMarkup) : null,
            'inline_message_id' => $inlineMessageId,
        ];
        return $this->post('editMessageText', $params);
    }

    /**
     * Send message to the telegram
     * @param $chatId
     * @param $text
     * @param null $replyMessageId
     * @param null $replyMarkup
     * @param string $parseMode
     * @param bool $disableUrlPreview
     * @param bool $disableNotification
     * @return mixed
     */
    public function sendMessage($chatId, $text, $replyMessageId = null, $replyMarkup = null, $parseMode = 'HTML', $disableUrlPreview = false, $disableNotification = false)
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'reply_to_message_id' => $replyMessageId,
            'reply_markup' => $replyMarkup ? json_encode($replyMarkup) : null,
            'parse_mode' => $parseMode,
            'disable_web_page_preview' => $disableUrlPreview,
            'disable_notification' => $disableNotification,
        ];

        return $this->post('sendMessage', $params);
    }

    /**
     * Forward a message
     * @param $chatId
     * @param $fromChatId
     * @param $messageId
     * @return mixed
     */
    public function forwardMessage($chatId, $fromChatId, $messageId)
    {
        $params = [
            'chat_id' => $chatId,
            'from_chat_id' => $fromChatId,
            'message_id' => $messageId,
        ];

        return $this->post('forwardMessage', $params);
    }

    /**
     * Send photo file to the telegram
     * @param $chatId
     * @param $file
     * @param null $caption
     * @param null $replyMessageId
     * @param null $replyMarkup
     * @return mixed
     */
    public function sendPhoto($chatId, $file, $caption = null, $replyMessageId = null, $replyMarkup = null)
    {
        $params = [
            'chat_id' => $chatId,
            'photo' => $file,
            'caption' => $caption,
            'reply_to_message_id' => $replyMessageId,
            'reply_markup' => $replyMarkup ? json_encode($replyMarkup) : null,
        ];

        return $this->post('sendPhoto', $params);
    }

    /**
     * Send an audio file to the telegram
     * @param $chatId
     * @param $file
     * @param $caption
     * @param null $replyToMessageId
     * @param null $replyMarkup
     * @return mixed
     */
    public function sendAudio($chatId, $file, $caption, $replyToMessageId = null, $replyMarkup = null)
    {
        $params = [
            'chat_id' => $chatId,
            'audio' => $file,
            'caption' => $caption,
            'reply_to_message_id' => $replyToMessageId,
            'reply_markup' => $replyMarkup ? json_encode($replyMarkup) : null,
        ];

        return $this->post('sendAudio', $params);
    }

    /**
     * Send a document file to the telegram
     * @param $chatId
     * @param $file
     * @param null $caption
     * @param null $replyToMessageId
     * @param null $replyMarkup
     * @return mixed
     */
    public function sendDocument($chatId, $file, $caption = null, $replyToMessageId = null, $replyMarkup = null)
    {
        $params = [
            'chat_id' => $chatId,
            'document' => $file,
            'caption' => $caption,
            'reply_to_message_id' => $replyToMessageId,
            'reply_markup' => $replyMarkup ? json_encode($replyMarkup) : null,
        ];

        return $this->post('sendDocument', $params);
    }

    /**
     * Send a video file to telegram chat
     * @param $chatId
     * @param $file
     * @param null $caption
     * @param null $replyMessageId
     * @param null $replyMarkup
     * @return mixed
     */
    public function sendVideo($chatId, $file, $caption = null, $replyMessageId = null, $replyMarkup = null)
    {
        $params = [
            'chat_id' => $chatId,
            'video' => $file,
            'caption' => $caption,
            'reply_to_message_id' => $replyMessageId,
            'reply_markup' => $replyMarkup ? json_encode($replyMarkup) : null,
        ];

        return $this->post('sendVideo', $params);
    }

    /**
     * Sends any type of files depending on its type
     * @param $file
     * @param $caption
     * @param null $replyMessageId
     * @return mixed
     */
    public function sendFile($chatId, $file, $caption, $replyMessageId = null, $replyMarkUp)
    {
        $send = $this->sendPhoto($chatId, $file, $caption, $replyMessageId, $replyMarkUp);
        if ($send->ok === false) {
            $send = $this->sendAudio($chatId, $file, $caption, $replyMessageId, $replyMarkUp);
            if ($send->ok === false) {
                $send = $this->sendVideo($chatId, $file, $caption, $replyMessageId, $replyMarkUp);
                if ($send->ok === false) {
                    $send = $this->sendDocument($chatId, $file, $caption, $replyMessageId, $replyMarkUp);
                }
            }
        }
        return $send;
    }

    /**
     * Send chat action to the chat status
     * @param $chatId
     * @param $action
     * @link https://core.telegram.org/bots/api#sendchataction
     * @return mixed
     */
    public function sendChatAction($chatId, $action)
    {
        $params['chat_id'] = $chatId;
        $params['action'] = $action;

        return $this->post('sendChatAction', $params);
    }

    /**
     * Builds a custom keyboard markup.
     * @param array $keyboard
     * @param bool  $resizeKeyboard
     * @param bool  $oneTimeKeyboard
     * @param bool  $selective
     * @link https://core.telegram.org/bots/api#replykeyboardmarkup
     * @return string
     */
    public function replyKeyboardMarkup($keyboard, $resizeKeyboard = true, $oneTimeKeyboard = true, $selective = true)
    {
        return [
            'keyboard' => $keyboard,
            'resize_keyboard' => $resizeKeyboard,
            'one_time_keyboard' => $oneTimeKeyboard,
            'selective' => $selective,
        ];
    }

    /**
     * Hide the current custom keyboard and display the default letter-keyboard.
     * @param bool $selective
     * @link https://core.telegram.org/bots/api#replykeyboardhide
     * @return string
     */
    public function replyKeyboardHide($selective = true)
    {
        return [
            'hide_keyboard' => true,
            'selective' => $selective,
        ];
    }

    /**
     * Display a reply interface to the user (act as if the user has selected the bot‘s message and tapped ’Reply').
     * @param bool $selective
     * @link https://core.telegram.org/bots/api#forcereply
     * @return string
     */
    public function forceReply($selective = true)
    {
        return [
            'force_reply' => true,
            'selective' => $selective,
        ];
    }
    
    /**
     * Returns file object valid for 1 hour
     * @param $file_id
     * @link https://core.telegram.org/bots/api#getfile
     * @return mixed
     */
    public function getFile($file_id)
    {
        return $this->get('getFile', compact('file_id'));
    }
    
    /**
     * Download file contents and save it to the destination path.
     *
     * @param string $filePath
     * @throws \Exception
     * @return mixed
     */
    public function downloadFile($filePath)
    {
        $curl = curl_init();
        $options = [
            CURLOPT_URL => $this->getBaseFileUrl() . $filePath,
            CURLOPT_HEADER => 0,
            CURLOPT_HTTPGET => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
        ];

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);

        if ($curlError = curl_error($curl)) {
            Yii::error(['message' => 'CURL Error', 'error' => curl_error($curl), 'filePath' => $filePath]);
        }
        curl_close($curl);

        return $response;
    }
    
    /**
     * Send request to the telegram
     * @param $method
     * @param $params
     * @return mixed
     */
    public function post($method, $params = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->apiUrl . $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        if ($curlError = curl_error($curl)) {
            Yii::error(['message' => 'CURL Error', 'error' => curl_error($curl), 'params' => $params]);
        }
        curl_close($curl);
        return json_decode($response);
    }

    /**
     * Get response from the telegram
     * @param $method
     * @param $params
     * @return mixed
     */
    public function get($method, $params = [])
    {
        $curl = curl_init();
        $stringParams = count($params) === 0 ? '' : '?' . http_build_query($params);
        curl_setopt($curl, CURLOPT_URL, $this->apiUrl . $method . $stringParams);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        if ($curlError = curl_error($curl)) {
            Yii::error(['message' => 'CURL Error', 'error' => curl_error($curl), 'params' => $params]);
        }
        curl_close($curl);
        return json_decode($response);
    }
}
