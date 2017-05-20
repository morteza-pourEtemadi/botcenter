<?php

namespace common\components\telegram;

use yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\helpers\Json;
use common\models\bot\Bot;
use common\models\bot\Users;
use common\models\bot\Subscribers;
use common\components\TelegramBot;
use common\components\telegram\types\Chat;
use common\components\telegram\types\Update;
use common\components\telegram\types\keyboards\InlineKeyboardButton;

/**
 * TelegramParser
 * Parse telegram requests
 *
 * @property Bot $bot
 * @property Update $update
 * @property TelegramBot $api
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class Parser extends Model
{
    /* @var Users $user */
    /* @var Chat $chat */

    public $bot;
    public $chat;
    public $user;

    private $_update;
    private $_commands;
    private $_chatId;

    /**
     * Returns telegram bot api object
     * @return TelegramBot
     */
    public function getApi()
    {
        return new TelegramBot(['authKey' => $this->bot->token]);
    }

    public function getUpdate()
    {
        return $this->_update;
    }

    public function setUpdate($params)
    {
        $this->_update = new Update($params);
    }

    /**
     * Returns this bot commands location
     * @return string
     */
    public function getCommandsLocation()
    {
        return 'botId_' . $this->bot->bot_id;
    }

    /**
     * Sets global chat id to use in code. whether its callback message or not
     * @return bool|int
     */
    public function setChat()
    {
        if (isset($this->update->message)) {
            $this->chat = $this->update->message->chat;
        } elseif (isset($this->update->callback_query->message)) {
            $this->chat = $this->update->callback_query->message->chat;
        } else {
            return false;
        }
        return $this->_chatId = $this->chat->id;
    }

    /**
     * Checks if user is subscribed to bot or not (in proper bot type, not all of them)
     * @return bool
     */
    public function checkUser()
    {
        $subscriber = Subscribers::findOne(['user_id' => $this->_chatId, 'bot_id' => $this->bot->bot_id]);
        if ($subscriber) {
            $settings = Json::decode($subscriber->getUser()->settings);
            $langID = isset($settings['language']) ? $settings['language'] : 1;
            Yii::$app->language = ($langID == 2) ? 'en-US' : 'fa-IR';

            if ($subscriber->status == Subscribers::STATUS_BLOCKED) {
                $subscriber->status = Subscribers::STATUS_ACTIVE;
                $subscriber->save();
            }
            if ($this->bot->type == Bot::TYPE_SUBSCRIPTION) {
                $membership = Json::decode($subscriber->memberString);
                if (!isset($membership['until']) || (time() > $membership['until'])) {
                    $this->notPayed();
                    return false;
                }
            }
        }
        return true;
    }
    
    /**
     * Parse telegram request
     */
    public function parse()
    {
        if ($this->setChat() === false) {
            return false;
        }
        if ($this->checkUser() === false) {
            return false;
        }

        return $this->parseCommands();
    }

    /**
     * Check message with available commands and execute appropriate command
     * @return bool
     */
    public function parseCommands()
    {
        $commands = $this->getCommands();
        if (count($commands) === 0) {
            return false;
        }

        $patterns = [];
        foreach ($commands as $command) {
            $patterns[] = $command->getPattern();
        }

        if (isset($this->update->message) === false && isset($this->update->callback_query->data) === false) {
            return false;
        }

        $pattern = '/start';

        if (isset($this->update->message->text)) {
            $translations = Json::decode($this->bot->translations);
            if (isset($translations[$this->update->message->text])) {
                $textWords = explode(' ', $translations[$this->update->message->text]);
            } else {
                $textWords = explode(' ', $this->update->message->text);
            }
        } elseif (isset($this->update->callback_query->data)) {
            $textWords = explode(' ', $this->update->callback_query->data);
        } elseif ($this->getReplyCommand() !== null) {
            $textWords = $this->getReplyCommand();
        } else {
            $textWords = [];
        }

        if (empty($textWords) === false) {
            $pattern = $textWords[0];
        }

        $commandIndex = array_search($pattern, $patterns, false);
        if ($commandIndex === false) {
            if ($this->getReplyCommand()) {
                $pattern = $this->getReplyCommand();
            } else {
                $pattern = '/start';
            }
            $commandIndex = array_search($pattern, $patterns, false);
        }

        $command = $commands[$commandIndex];
        $command->run();
        return true;
    }

    /**
     * Returns all available commands
     * @return array
     */
    public function getCommands()
    {
        if ($this->_commands) {
            return $this->_commands;
        }
        $path = Yii::getAlias('@common/components/telegram/commands/' . $this->getCommandsLocation());
        $namespace = '\common\components\telegram\commands\\' . $this->getCommandsLocation();
        $files = glob($path . '/*.php');
        $commands = [];

        foreach ($files as $file) {
            if (preg_match('/.*\/([\w]+Command).php/', $file, $matches)) {
                $className = $namespace . '\\' . $matches[1];
                $commands[] = new $className(['bot' => $this->bot, 'update' => $this->update]);
            }
        }

        $this->_commands = $commands;
        return $commands;
    }

    /**
     * Returns reply command if is set
     * @return mixed | null
     */
    public function getReplyCommand()
    {
        $reply = Yii::$app->cache->get('reply:' . $this->bot->bot_id . $this->_chatId);
        return isset($reply['command']) ? $reply['command'] : null;
    }

    /**
     * Force a message to pay subscribing fee, if user is not subscribed.
     * @return bool
     */
    public function notPayed()
    {
        $key = [];
        $marketing = Json::decode($this->bot->priceString);

        foreach ($marketing['price'] as $id => $price) {
            $time = $marketing['time'][$id];
            $description = Yii::t('app_bot', 'the fee for this bot is {price} Rials for {vt}', ['price' => $price, 'vt' => $this->calcTime($time)]);
            $payId = implode(
                '.ubpd.',
                [
                    base64_encode($this->_chatId),
                    base64_encode($this->bot->bot_id),
                    base64_encode($price),
                    base64_encode($description),
                ]
            );
            $key[] = InlineKeyboardButton::setNewKeyButton(
                Yii::t('app_bot', 'buy {vt} subscription for {price} toman', ['price' => $price, 'vt' => $this->calcTime($time)]),
                '', Url::to(['payment/receipt', 'pay_id' => $payId], true)
            );
        }

        $message = Yii::t('app_bot', 'You have not payed your subscription fee yet or its validation time has expired. you need to pay now');

        $keyRows = [];
        foreach ($key as $value) {
            $keyRows[] = [$value];
        }

        $keyboard = [
            'inline_keyboard' => $keyRows
        ];
        $this->api->sendMessage($this->_chatId, $message, null, $keyboard, 'HTML');
        return false;
    }

    /**
     * Turns seconds to month/week/day !
     * @param $time
     * @return string
     */
    public function calcTime($time)
    {
        $dayLong = 3600*24;
        $weekLong = 3600*24*7;
        $monthLong = 3600*24*30;

        $month = floor($time / ($monthLong));
        $week = floor(($time - ($month * $monthLong)) / $weekLong);
        $day = floor(($time - ($month * $monthLong) - ($week * $weekLong)) / $dayLong);

        $returnTime = $month > 0 ? $month . Yii::t('app_bot', 'month') : '';
        $returnTime .= $week > 0 ? $week . Yii::t('app_bot', 'week') : '';
        $returnTime .= $day > 0 ? $day . Yii::t('app_bot', 'day') : '';

        return $returnTime;
    }
}
