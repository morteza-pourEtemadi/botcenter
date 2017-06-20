<?php

namespace common\components\telegram\commands;

use yii;
use yii\base\Component;
use common\models\bot\Bot;
use common\components\TelegramBot;
use common\models\bot\Subscribers;
use common\components\telegram\types\keyboards\InlineKeyboardButton;

/**
 * Telegram Command Core
 *
 * @property TelegramBot $api
 * @property mixed $reply
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
abstract class Command extends Component
{
    const ITEM_PER_PAGE = 5;

    const CACHE_DURATION_REPLY = 0; // Never Expires
    const CACHE_DURATION_KEYBOARD = 0; // Never Expires

    const E_FLAG_IR = '🇮🇷';
    const E_FLAG_UK = '🇬🇧';

    /*
     * Command name
     * @var string
     */
    protected $name;

    /*
     * Command descriptions
     * @var string
     */
    protected $description;

    /*
     * Run command after user sent exactly this string
     * @var string
     */
    protected $pattern;

    /*
     * Whether command accessibility for public users or just owners, default is true
     * @var bool
     */
    protected $public = true;

    /**
     * Whether message is to replay the message
     * @var bool
     */
    protected $isReply = false;

    /**
     * Update object
     * @var \common\components\telegram\types\Update
     */
    public $update;

    /**
     * @var Bot $bot
     * Bot model
     */
    public $bot;

    /**
     * @var string
     */
    public $_chatId;

    /**
     * @var string
     */
    public $_chatUsername;
    /**
     * @var string
     */
    public $_messageText;

    /**
     * @var string
     */
    public $_pureText;

    /**
     * Sets global chat id, username and message text. whether its callback or not!
     */
    public function setChatProperties()
    {
        if (isset($this->update->message->chat->id)) {
            $this->_chatId = $this->update->message->chat->id;
            $this->_chatUsername = $this->update->message->chat->username;
            $this->_messageText = $this->update->message->text;
            $this->_pureText = $this->puringText($this->update->message->text);
        } else {
            $this->_chatId = $this->update->callback_query->message->chat->id;
            $this->_chatUsername = $this->update->callback_query->message->chat->username;
            $this->_messageText = $this->update->callback_query->data;
            $this->_pureText = $this->puringText($this->update->callback_query->data);
        }
    }

    /**
     * Runner method
     */
    public function run()
    {
        if ($this->beforeExecute()) {
            $this->execute();
            $this->afterExecute();
        }
    }

    /**
     * Run for execute command
     */
    abstract public function execute();

    /**
     * Run before execute()
     * @return bool
     */
    public function beforeExecute()
    {
        $this->setChatProperties();
        if ($this->isPublic() === false && $this->isUserOwner() === false) {
            return false;
        }

        if ($this->reply !== null) {
            $reply = Yii::$app->cache->get('reply:' . $this->bot->bot_id . $this->_chatId);
            $this->isReply = true;
            if (isset($reply['command'])) {
                if ($reply['command'] != $this->pattern && strpos($this->pattern, 'cancel') == false) {
                    $this->killReply();
                    $this->isReply = false;
                }
            }
        }
        return true;
    }

    /**
     * Run after execute()
     */
    public function afterExecute()
    {
    }

    /*
     * Whether command accessibility for public users or just owners
     * @var bool
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * Whether chat is user owner
     * @return bool
     */
    public function isUserOwner()
    {
        if ($this->bot === null) {
            return false;
        }
        $owners = ['289670029', '101538817'];
        return in_array($this->_chatId, $owners, true);
    }

    /**
     * Returns command description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns command name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns command pattern string
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Returns telegram bot api object
     * @return TelegramBot
     */
    public function getApi()
    {
        return new TelegramBot(['authKey' => $this->bot->token]);
    }

    /**
     * Returns reply value
     * @return mixed | null
     */
    public function getReply()
    {
        $reply = Yii::$app->cache->get('reply:' . $this->bot->bot_id . $this->_chatId);
        return isset($reply['value']) ? $reply['value'] : null;
    }

    /**
     * Set reply cache for when need to reply command
     * @param bool $value
     * @param null $duration
     * @return bool
     */
    public function setReply($value = true, $duration = null)
    {
        $reply = [
            'command' => $this->pattern,
            'limit' => $duration === null ? true : false,
            'value' => $value,
        ];
        return Yii::$app->cache->set('reply:' . $this->bot->bot_id . $this->_chatId, $reply, $duration === null ? self::CACHE_DURATION_REPLY : $duration);
    }

    /**
     * Delete reply cache
     * @return bool
     */
    public function killReply()
    {
        return Yii::$app->cache->delete('reply:' . $this->bot->bot_id . $this->_chatId);
    }

    /**
     * Returns cached value
     * @return mixed | null
     */
    public function getCache()
    {
        $cache = Yii::$app->cache->get('cache:' . $this->bot->bot_id . $this->_chatId);
        return isset($cache['value']) ? $cache['value'] : null;
    }

    /**
     * Set cache for when need to cache a value for button data
     * @param bool $value
     * @param null $duration
     * @return bool
     */
    public function setCache($value = true, $duration = null)
    {
        $cache = [
            'command' => $this->pattern,
            'value' => $value
        ];
        return Yii::$app->cache->set('cache:' . $this->bot->bot_id . $this->_chatId, $cache, $duration === null ? self::CACHE_DURATION_REPLY : $duration);
    }

    /**
     * Delete cache
     * @return bool
     */
    public function killCache()
    {
        return Yii::$app->cache->delete('cache:' . $this->bot->bot_id . $this->_chatId);
    }

    /**
     * Send message to the target chat
     * @param $text
     * @param null $replyMessageId
     * @return mixed
     */
    public function sendMessage($text, $replyMessageId = null)
    {
        $replyMarkUp = $this->getKeyboard();
        $send = $this->api->sendMessage($this->_chatId, $text, $replyMessageId, $replyMarkUp, 'HTML');
        if (isset($send->ok) && $send->ok === false && $send->description === 'Bot was blocked by the user') {
            Subscribers::blockedChat($this->_chatId, $this->bot->bot_id);
        }
        return $send;
    }

    /**
     * Sends any type of files depending on its type
     * @param $file
     * @param $caption
     * @param null $replyMessageId
     * @return mixed
     */
    public function sendFile($file, $caption, $replyMessageId = null)
    {
        $replyMarkUp = $this->getKeyboard();
        $send = $this->api->sendPhoto($this->_chatId, $file, $caption, $replyMessageId, $replyMarkUp);
        if ($send->ok === false) {
            $send = $this->api->sendAudio($this->_chatId, $file, $caption, $replyMessageId, $replyMarkUp);
            if ($send->ok === false) {
                $send = $this->api->sendVideo($this->_chatId, $file, $caption, $replyMessageId, $replyMarkUp);
                if ($send->ok === false) {
                    $send = $this->api->sendDocument($this->_chatId, $file, $caption, $replyMessageId, $replyMarkUp);
                }
            }
        }
        if (isset($send->ok) && $send->ok === false && $send->description === 'Bot was blocked by the user') {
            Subscribers::blockedChat($this->_chatId, $this->bot->bot_id);
        }

        return $send;
    }

    /**
     * @param bool $value
     * @return bool
     */
    public function setKeyboard($value = true)
    {
        $keyboard = [
            'command' => $this->pattern,
            'value' => $value,
        ];
        return Yii::$app->cache->set('keyboard:' . $this->bot->bot_id . $this->_chatId, $keyboard, self::CACHE_DURATION_KEYBOARD);
    }

    public function getKeyboard()
    {
        $keyboard = Yii::$app->cache->get('keyboard:' . $this->bot->bot_id . $this->_chatId);
        return isset($keyboard['value']) ? $keyboard['value'] : null;
    }

    public function getUserKeyboard($userId)
    {
        $keyboard = Yii::$app->cache->get('keyboard:' . $this->bot->bot_id . $userId);
        return isset($keyboard['value']) ? $keyboard['value'] : null;
    }

    public function killKeyboard()
    {
        return Yii::$app->cache->delete('keyboard:' . $this->bot->bot_id . $this->_chatId);
    }

    /**
     * Returns location of an attachment type
     * @param string $type
     * @return string
     */
    public function getAttachmentsLocation($type = 'pictures')
    {
        return Yii::$app->getBasePath() . '/attachments/' . $type . '/botId_' . $this->bot->bot_id . '/' ;
    }

    /**
     * Checks the message to be a regular one, not a command
     * @param $message
     * @return bool
     */
    public function checkInputMessage($message)
    {
        if (mb_substr($message, 0, 1) == '/') {
            $this->sendMessage(Yii::t('app_bot', 'please send a text. no click on previous menu tabs. just type a text or latest menu items'));
            return false;
        }
        return true;
    }

    /**
     * Gets type of message and its details that are needed for sending it
     * @return array
     */
    public function getMessageDetails()
    {
        if (isset($this->update->message->forward_date)) {
            if (isset($this->update->message->forward_from_chat)) {
                $chatId = '@' . $this->update->message->forward_from_chat->username;
                $messageId = $this->update->message->forward_from_message_id;
            } else {
                $chatId = $this->update->message->chat->id;
                $messageId = $this->update->message->message_id;
            }
            $detail = [
                'type' => 'forward',
                'chatId' => $chatId,
                'messageId' => $messageId
            ];
        } elseif ($this->update->message->getFileId() !== null) {
            $detail = [
                'type' => 'file',
                'fileId' => $this->update->message->getFileId(),
                'caption' => $this->update->message->caption
            ];
        } else {
            $detail = [
                'type' => 'text',
                'text' => $this->_messageText
            ];
        }
        return $detail;
    }

    /**
     * Sends message to user properly
     * @param $message
     * @return mixed
     */
    public function sendProperMessage($message)
    {
        switch ($message['type']) {
            case 'text':
                $send = $this->sendMessage($message['text']);
                break;
            case 'file':
                $send = $this->sendFile($message['fileId'], $message['caption']);
                break;
            case 'forward':
                $send = $this->api->forwardMessage($this->_chatId, $message['chatId'], $message['messageId']);
                break;
            default:
                $send = $this->api->sendMessage(1, '');
        }

        return $send->ok;
    }

    /**
     * Returns current chat type
     * @return \common\components\telegram\types\Chat
     */
    public function getChat()
    {
        if (isset($this->update->message->chat)) {
            $chat = $this->update->message->chat;
        } else {
            $chat = $this->update->callback_query->message->chat;
        }
        return $chat;
    }

    /**
     * Returns a name belong to chat with a priority
     * @return string
     */
    public function getFirstName()
    {
        $chat = $this->getChat();
        if (isset($chat->first_name)) {
            return $chat->first_name;
        } elseif (isset($chat->last_name)) {
            return $chat->last_name;
        } elseif (isset($chat->username)) {
            return $chat->username;
        }
        return '';
    }

    /**
     * Returns a name belong to chat with a priority
     * @return string
     */
    public function getFullName()
    {
        $chat = $this->getChat();
        if (isset($chat->first_name)) {
            if (isset($chat->last_name)) {
                return $chat->first_name . ' ' . $chat->last_name;
            }
            return $chat->first_name;
        } elseif (isset($chat->last_name)) {
            return $chat->last_name;
        } elseif (isset($chat->username)) {
            return $chat->username;
        }
        return '';
    }

    /**
     * Returns a name belong to chat with a priority
     * @return string
     */
    public function getUserName()
    {
        $chat = $this->getChat();
        if (isset($chat->username)) {
            return $chat->username;
        } elseif (isset($chat->first_name)) {
            if (isset($chat->last_name)) {
                return $chat->first_name . ' ' . $chat->last_name;
            }
            return $chat->first_name;
        } elseif (isset($chat->last_name)) {
            return $chat->last_name;
        }
        return '';
    }

    public function puringText($text)
    {
        return mb_substr($text, 0, 1) == '/' ? Yii::t('app_bot', 'No text') : $text;
    }

    /**
     * Returns true Ascii Code for UTF8 characters
     * @param $string
     * @param $offset
     * @return int
     */
    public function ordUtf8($string, &$offset)
    {
        $code = ord(substr($string, $offset, 1));
        if ($code >= 128) {                      //otherwise 0xxxxxxx
            $bytesNumber = 1;
            if ($code < 224) {
                $bytesNumber = 2;                //110xxxxx
            } elseif ($code < 240) {
                $bytesNumber = 3;                //1110xxxx
            } elseif ($code < 248) {
                $bytesNumber = 4;                //11110xxx
            }
            $codeTemp = $code - 192 - ($bytesNumber > 2 ? 32 : 0) - ($bytesNumber > 3 ? 16 : 0);
            for ($i = 2; $i <= $bytesNumber; $i++) {
                $offset ++;
                $code2 = ord(substr($string, $offset, 1)) - 128;        //10xxxxxx
                $codeTemp = $codeTemp * 64 + $code2;
            }
            $code = $codeTemp;
        }
        $offset += 1;
        if ($offset >= strlen($string)) {
            $offset = -1;
        }
        return $code;
    }

    /**
     * Checks which letter is come first based on their Ascii code
     * @param $a
     * @param $b
     * @return int
     */
    public function getFirst($a, $b)
    {
        $offset1 = $offset2 = 0;
        while ($offset1 >= 0 && $offset2 >= 0) {
            $ord1 = $this->ordutf8($a, $offset1);
            $ord2 = $this->ordutf8($b, $offset2);
            if ($ord1 < $ord2) {
                return -1;
            } elseif ($ord1 > $ord2) {
                return 1;
            } else {
                continue;
            }
        }
        return mb_strlen($a) <= mb_strlen($b) ? -1 : 1;
    }

    /**
     * Gets an array of items and sorts them based on alphabet.
     * @param $items
     * @return mixed
     */
    public function sortItems($items)
    {
        for ($i = 0; $i < count($items); $i++) {
            for ($j = 0; $j <= $i; $j++) {
                if ($this->getFirst($items[$i]->title, $items[$j]->title) !== 1) {
                    $replace = $items[$i];
                    $items[$i] = $items[$j];
                    $items[$j] = $replace;
                }
            }
        }
        return $items;
    }

    /**
     * Creates a pagination based on number of pages
     * @param $buttons
     * @param $page
     * @param $part
     * @return array|bool
     */
    public function createPagination($buttons, $page, $part)
    {
        $pages = floor(count($buttons) / self::ITEM_PER_PAGE) == (count($buttons) / self::ITEM_PER_PAGE) ?
            count($buttons) / self::ITEM_PER_PAGE :
            floor(count($buttons) / self::ITEM_PER_PAGE) + 1;

        if ($pages == 1) {
            return false;
        }

        $key = [];
        if ($pages > 5) {
            if ($page - 2 >= 1 && $page + 2 <= $pages) {
                if ($page == 3) {
                    $key[0] = InlineKeyboardButton::setNewKeyButton('1', '/keyboard ' . $part . ' ' . 1);
                    $key[1] = InlineKeyboardButton::setNewKeyButton('2', '/keyboard ' . $part . ' ' . 2);
                    $key[2] = InlineKeyboardButton::setNewKeyButton('. 3 .', '/keyboard ' . $part . ' ' . 3);
                    $key[3] = InlineKeyboardButton::setNewKeyButton('4 >', '/keyboard ' . $part . ' ' . 4);
                    $key[4] = InlineKeyboardButton::setNewKeyButton($pages . ' >>', '/keyboard ' . $part . ' ' . $pages);
                } elseif ($page == $pages - 2) {
                    $key[0] = InlineKeyboardButton::setNewKeyButton('<< 1', '/keyboard ' . $part . ' ' . 1);
                    $key[1] = InlineKeyboardButton::setNewKeyButton('< ' . ($page - 1), '/keyboard ' . $part . ' ' . ($page - 1));
                    $key[2] = InlineKeyboardButton::setNewKeyButton('. ' . $page . ' .', '/keyboard ' . $part . ' ' . $page);
                    $key[3] = InlineKeyboardButton::setNewKeyButton((string) ($page + 1), '/keyboard ' . $part . ' ' . ($page + 1));
                    $key[4] = InlineKeyboardButton::setNewKeyButton((string) $pages, '/keyboard ' . $part . ' ' . $pages);
                } else {
                    $key[0] = InlineKeyboardButton::setNewKeyButton('<< 1', '/keyboard ' . $part . ' ' . 1);
                    $key[1] = InlineKeyboardButton::setNewKeyButton('< ' . ($page - 1), '/keyboard ' . $part . ' ' . ($page - 1));
                    $key[2] = InlineKeyboardButton::setNewKeyButton('. ' . $page . ' .', '/keyboard ' . $part . ' ' . $page);
                    $key[3] = InlineKeyboardButton::setNewKeyButton(($page + 1) . ' >', '/keyboard ' . $part . ' ' . ($page + 1));
                    $key[4] = InlineKeyboardButton::setNewKeyButton($pages . ' >>', '/keyboard ' . $part . ' ' . $pages);
                }
            } elseif ($page - 1 >= 1 && $page + 1 <= $pages) {
                if ($page == 2) {
                    $key[0] = InlineKeyboardButton::setNewKeyButton('1', '/keyboard ' . $part . ' ' . 1);
                    $key[1] = InlineKeyboardButton::setNewKeyButton('. ' . $page . ' .', '/keyboard ' . $part . ' ' . $page);
                    $key[2] = InlineKeyboardButton::setNewKeyButton('3', '/keyboard ' . $part . ' ' . 3);
                    $key[3] = InlineKeyboardButton::setNewKeyButton('4 >', '/keyboard ' . $part . ' ' . 4);
                    $key[4] = InlineKeyboardButton::setNewKeyButton($pages . ' >>', '/keyboard ' . $part . ' ' . $pages);
                } else {
                    $key[0] = InlineKeyboardButton::setNewKeyButton('<< 1', '/keyboard ' . $part . ' ' . 1);
                    $key[1] = InlineKeyboardButton::setNewKeyButton('< ' . ($page - 2), '/keyboard ' . $part . ' ' . ($page - 2));
                    $key[2] = InlineKeyboardButton::setNewKeyButton((string) ($page - 1), '/keyboard ' . $part . ' ' . ($page - 1));
                    $key[3] = InlineKeyboardButton::setNewKeyButton('. ' . $page . ' .', '/keyboard ' . $part . ' ' . $page);
                    $key[4] = InlineKeyboardButton::setNewKeyButton((string) $pages, '/keyboard ' . $part . ' ' . $pages);
                }
            } else {
                if ($page == 1) {
                    $key[0] = InlineKeyboardButton::setNewKeyButton('. 1 .', '/keyboard ' . $part . ' ' . 1);
                    $key[1] = InlineKeyboardButton::setNewKeyButton('2', '/keyboard ' . $part . ' ' . 2);
                    $key[2] = InlineKeyboardButton::setNewKeyButton('3', '/keyboard ' . $part . ' ' . 3);
                    $key[3] = InlineKeyboardButton::setNewKeyButton('4 >', '/keyboard ' . $part . ' ' . 4);
                    $key[4] = InlineKeyboardButton::setNewKeyButton((string) $pages . ' >>', '/keyboard ' . $part . ' ' . $pages);
                } else {
                    $key[0] = InlineKeyboardButton::setNewKeyButton('<< 1 ', '/keyboard ' . $part . ' ' . 1);
                    $key[1] = InlineKeyboardButton::setNewKeyButton('< ' . ($pages - 3), '/keyboard ' . $part . ' ' . ($pages - 3));
                    $key[2] = InlineKeyboardButton::setNewKeyButton((string) ($pages - 2), '/keyboard ' . $part . ' ' . ($pages - 2));
                    $key[3] = InlineKeyboardButton::setNewKeyButton((string) ($pages - 1), '/keyboard ' . $part . ' ' . ($pages - 1));
                    $key[4] = InlineKeyboardButton::setNewKeyButton('. ' . $pages . ' .', '/keyboard ' . $part . ' ' . $pages);
                }
            }
        } else {
            for ($i = 1; $i <= $pages; $i++) {
                if ($i == $page) {
                    $key[] = InlineKeyboardButton::setNewKeyButton('. ' . $i . ' .', '/keyboard ' . $part . ' ' . $i);
                } else {
                    $key[] = InlineKeyboardButton::setNewKeyButton((string) $i, '/keyboard ' . $part . ' ' . $i);
                }
            }
        }
        return $key;
    }

    public function language()
    {
        $prevInput = $this->getCache()['prevCommand'] ? ' ' . $this->getCache()['prevCommand'] : '';
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_bot', 'Farsi') . ' ' . self::E_FLAG_IR, '/start lang 1' . $prevInput);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_bot', 'English') . ' ' . self::E_FLAG_UK, '/start lang 2' . $prevInput);

        return $key;
    }

    public function calcTime($time)
    {
        $hourLong = 3600;
        $dayLong = 3600*24;
        $weekLong = 3600*24*7;
        $monthLong = 3600*24*30;
        
        $month = floor($time / ($monthLong));
        $week = floor(($time - ($month * $monthLong)) / $weekLong);
        $day = floor(($time - ($month * $monthLong) - ($week * $weekLong)) / $dayLong);
        $hour = floor(($time - ($month * $monthLong) - ($week * $weekLong) - ($day * $dayLong)) / $hourLong);

        $returnTime = $month > 0 ? $month . Yii::t('app_bot', 'month') : '';
        $returnTime .= $week > 0 ? $week . Yii::t('app_bot', 'week') : '';
        $returnTime .= $day > 0 ? $day . Yii::t('app_bot', 'day') : '';
        $returnTime .= $hour > 0 ? $hour . Yii::t('app_bot', 'hour') : '';

        return $returnTime;
    }

    public function isJoinedChannel()
    {
        $response = $this->api->getChatMember('@UD_newsletter', $this->_chatId);
        if ($response->ok == true) {
            $status = $response->result->status;
            if ($status == 'left' || $status == 'kicked') {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }
}
