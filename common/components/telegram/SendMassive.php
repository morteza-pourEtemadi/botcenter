<?php
namespace common\components\telegram;

use Yii;
use common\models\Bot;
use common\models\Subscribers;
use common\components\TelegramBot;
use common\components\telegram\types\User;

/**
 * Class SendMassive
 * @package common\components\telegram
 *
 * @property Bot $bot;
 * @property TelegramBot $api
 */
class SendMassive
{
    public $api;
    public $bot;

    public function getApi()
    {
        return new TelegramBot(['authKey' => $this->bot->token]);
    }

    public function getKeyboard($chatId = null)
    {
        $keyboard = Yii::$app->cache->get('keyboard:' . $this->bot->bot_id . $chatId);
        return isset($keyboard['value']) ? $keyboard['value'] : null;
    }

    public function sendMultipleMessagesToMultipleUsers($messages = [], $people = null)
    {
        if ($people === null) {
            $people = Subscribers::find()->andWhere(['bot_id' => $this->bot->bot_id])->asArray()->all();
        }

        foreach ($people as $person) {
            $subMessages = $messages[$person['user_id']];
            unset($subMessages[count($subMessages) - 1]);

            foreach ($subMessages as $message) {
                $this->getApi()->sendMessage($person['user_id'], $message, null, null, 'HTML');
            }
            $this->getApi()->sendMessage($person['user_id'], end($messages[$person['user_id']]), null, $this->getKeyboard($person['user_id']), 'HTML');
        }
        return true;
    }
    
    public function sendOneMessageToMultipleUsers($message = '', $people = null)
    {
        if ($people === null) {
            $people = Subscribers::find()->andWhere(['bot_id' => $this->bot->bot_id])->asArray()->all();
        }

        foreach ($people as $person) {
            $this->getApi()->sendMessage($person['user_id'], $message, null, $this->getKeyboard($person['user_id']), 'HTML');
        }
        return true;
    }

    public function sendOneFileToMultipleUsers($file = '', $caption = '', $people = null)
    {
        if ($people === null) {
            $people = Subscribers::find()->andWhere(['bot_id' => $this->bot->bot_id])->asArray()->all();
        }

        foreach ($people as $person) {
            $replyMarkUp = $this->getKeyboard($person['user_id']);
            $send = $this->getApi()->sendPhoto($person['user_id'], $file, $caption, null, $replyMarkUp);
            if ($send->ok === false) {
                $send = $this->getApi()->sendVideo($person['user_id'], $file, $caption, null, $replyMarkUp);
                if ($send->ok === false) {
                    $send = $this->getApi()->sendDocument($person['user_id'], $file, $caption, null, $replyMarkUp);
                    if ($send->ok === false) {
                        $send = $this->getApi()->sendAudio($person['user_id'], $file, $caption, null, $replyMarkUp);
                    }
                }
            }
        }
        return true;
    }

    public function sendOneMessageWithCustomKeyboard($message = '', $people = null, $keyboard)
    {
        if ($people === null) {
            $people = Subscribers::find()->andWhere(['bot_id' => $this->bot->bot_id])->asArray()->all();
        }

        foreach ($people as $person) {
            $this->getApi()->sendMessage($person['user_id'], $message, null, $keyboard, 'HTML', true);
        }
        return true;
    }

    public function forwardToMultipleUsers($fromChatId, $messageId, $people = null)
    {
        if ($people === null) {
            $people = Subscribers::find()->andWhere(['bot_id' => $this->bot->bot_id])->asArray()->all();
        }

        foreach ($people as $person) {
            $this->getApi()->forwardMessage($person['user_id'], $fromChatId, $messageId);
        }
        return true;
    }
}
