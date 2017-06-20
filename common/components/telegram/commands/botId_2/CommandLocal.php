<?php

namespace common\components\telegram\commands\botId_2;

use yii;
use common\components\TelegramBot;
use common\components\telegram\commands\Command;
use common\components\telegram\types\keyboards\KeyboardButton;
use common\components\telegram\types\keyboards\InlineKeyboardButton;

/**
 * Telegram Command Core for Local Purposes
 *
 * @property TelegramBot $api
 * @property mixed $reply
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
abstract class CommandLocal extends Command
{
    public function getKhatms()
    {
        $items = $this->getCache()['ktm_ids'];
        foreach ($items as $item) {
            $key[] = InlineKeyboardButton::setNewKeyButton($item['title'], '/join ' . $item['id']);
        }
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'back to main menu'), '/start');

        return $key;
    }

    public function joinKhatm()
    {
        $id = $this->getCache()['ktm_id'];

        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'join this khatm'), '/join ' . $id . ' 1');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'back to list'), '/join');
        return $key;
    }

    public function enterNum()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'cancel'), '/join cancel');
    }

    public function addKhatm()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'cancel'), 'addKhatm cancel');
        return $key;
    }

    public function khatmTypes()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'Type Aya'), 'addKhatm 1');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'Type Page'), 'addKhatm 2');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'Type Joz'), 'addKhatm 3');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'cancel'), 'addKhatm cancel');

        return $key;
    }

    public function showJoinedKtm()
    {
        $id = $this->getCache()['id'];

        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'khatm menu'), '/ktmMenu ' . $id);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'back to list'), '/join');

        return $key;
    }

    public function ktmMenu()
    {
        $x = $this->getCache()['x'];
        $id = $this->getCache()['id'];

        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'get {x}', ['x' => $x]), '/get share ' . $id);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'dismiss the khatm'), '/get dismiss ' . $id);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'read my share'), '/get rms ' . $id);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'back to list'), '/join');

        return $key;
    }

    public function dismiss()
    {
        $id = $this->getCache()['id'];

        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'read and dismiss'), '/get dismissF ' . $id);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'back to menu'), '/ktmMenu ' . $id);

        return $key;
    }

    /**
     * Sets inline keyboard based on the parts, decorations and with pagination
     * @param $part
     * @param string $decor
     * @param int $page
     */
    public function setPartKeyboard($part, $page = 1, $decor = 'linear')
    {
        $key = $this->$part();
        $selectedKeys = [];
        $keyboard = [];

        if (count($key) > 5) {
            $endButton = end($key);
            $n = count($key) - 1;
            unset($key[$n]);
            $limit = count($key) > $page * self::ITEM_PER_PAGE ? $page * self::ITEM_PER_PAGE : count($key);
            for ($i = ($page - 1) * self::ITEM_PER_PAGE; $i < $limit; $i++) {
                $selectedKeys[] = $key[$i];
            }
            $pagination = $this->createPagination($key, $page, $part);
        } else {
            $selectedKeys = $key;
        }

        switch ($decor) {
            case 'linear':
                for ($i = 0; $i < count($selectedKeys); $i++) {
                    $keyboard[] = [$selectedKeys[$i]];
                }
                break;
        }

        if (isset($pagination) && $pagination !== false && isset($endButton)) {
            $keyboard[] = $pagination;
            $keyboard[] = [$endButton];
        }

        $this->setKeyboard([
            'inline_keyboard' => $keyboard,
        ]);
    }

    /**
     * Sets the custom keyboard. It's the main menu of the bot.
     */
    public function setMainKeyboard()
    {
        $key = [];

        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_2', 'See khatms lists') . ' 📑');
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_2', 'Random Aya') . ' 📤');
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_2', 'Hadith of the day') . ' 📜');
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_2', 'Guide') . ' 📚');
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_2', 'About Us') . ' 🔖');

        $keyboard = [[$key[0]], [$key[1], $key[2]], [$key[3], $key[4]]];
        $this->setKeyboard([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);
    }

    public function beforeExecute()
    {
        parent::beforeExecute();
        // @TODO: For Test Reasons! REMOVE...
        if ($this->_chatId != 101538817) {
            return false;
        }
        return true;
    }
}
