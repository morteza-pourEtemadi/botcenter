<?php

namespace common\components\telegram;

use yii;
use yii\base\Model;
use yii\helpers\Html;
use common\models\bot\Bot;
use common\components\TelegramBot;
use common\components\telegram\types\Sender;

/**
 * TelegramParser
 * Parse telegram requests
 *
 * @property Bot $bot
 * @property Sender $sender
 * @property TelegramBot $api
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class SendParser extends Model
{
    public $bot;
    private $_sender;

    /**
     * Returns telegram bot api object
     * @return TelegramBot
     */
    public function getApi()
    {
        return new TelegramBot(['authKey' => $this->bot->token]);
    }

    public function getSender()
    {
        return $this->_sender;
    }

    public function setSender($params)
    {
        $this->_sender = new Sender($params);
    }

    /**
     * Parse telegram request
     */
    public function parse()
    {
        if ($this->sender->from_chat_id != null && $this->sender->message_id != null) {
            $type = 1;
        } elseif ($this->sender->file_id != null && $this->sender->caption != null) {
            $type = 2;
        } elseif ($this->sender->text != null) {
            $type = 3;
        } else {
            return false;
        }

        $replyMarkUp = null;
        $keyboard = [];
        if ($this->sender->keys != null && $this->sender->inline == null) {
            if ($this->sender->sort == null) {
                foreach ($this->sender->keys as $key) {
                    $keyboard[] = [$key];
                }
            } else {
                $c = 0;
                foreach ($this->sender->sort as $item) {
                    $c += $item;
                }
                if (count($this->sender->keys) != $c) {
                    return false;
                }
                $j = 0;
                foreach ($this->sender->sort as $item) {
                    $line = [];
                    for ($i = $j; $i <= $item + $j; $i++) {
                        $line[] = $this->sender->keys[$i];
                    }
                    $keyboard[] = $line;
                }
            }
            $replyMarkUp = [
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ];
        } elseif ($this->sender->keys == null && $this->sender->inline != null) {
            if ($this->sender->sort == null) {
                foreach ($this->sender->inline as $key) {
                    $keyboard[] = [$key];
                }
            } else {
                $c = 0;
                foreach ($this->sender->sort as $item) {
                    $c += $item;
                }
                if (count($this->sender->inline) != $c) {
                    return false;
                }
                $j = 0;
                foreach ($this->sender->sort as $item) {
                    $line = [];
                    for ($i = $j; $i < $item + $j; $i++) {
                        $line[] = $this->sender->inline[$i];
                    }
                    $keyboard[] = $line;
                    $j += $item;
                }
            }
            $replyMarkUp = [
                'inline_keyboard' => $keyboard
            ];
        }

        $banned = $finished = $failed = [];
        $messages = 0;
        $t = time();
        foreach ($this->sender->users as $user) {
            /* @var \common\components\telegram\types\User $user */
            if ($messages >= 29) {
                if ((time() - $t) <= 1) {
                    sleep(1);
                }
                $messages = 0;
                $t = time();
            }

            if ($type == 1) {
                $sent = $this->getApi()->forwardMessage($user->id, $this->sender->from_chat_id, $this->sender->message_id);
            } elseif ($type == 2) {
                $sent = $this->getApi()->sendFile($user->id, $this->sender->file_id, $this->sender->caption, null, $replyMarkUp);
            } else {
                $text = ($this->sender->url != null ? Html::a('&#160;', $this->sender->url) : '') . $this->sender->text;
                $sent = $this->getApi()->sendMessage($user->id, $text, null, $replyMarkUp);
            }
            if ($sent->ok == false && $sent->error_code == 403) {
                $banned[] = [
                    'user_id' => $user->id
                ];
            } elseif($sent->ok == true) {
                $finished[] = [
                    'user_id' => $user->id,
                    'message_id' => $sent->result->message_id
                ];
                $messages++;
            } else {
                $failed[] = [
                    'user_id' => $user->id,
                    'error' => $sent->description
                ];
            }
        }
        $ret = [
            'success' => $finished,
            'banned' => $banned,
            'failed' => $failed
        ];
        return yii\helpers\Json::encode($ret);
    }
}
