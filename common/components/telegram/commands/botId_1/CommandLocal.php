<?php

namespace common\components\telegram\commands\botId_1;

use common\components\telegram\types\keyboards\InlineKeyboardButton;
use yii;
use common\components\TelegramBot;
use common\components\telegram\commands\Command;
use common\components\telegram\types\keyboards\KeyboardButton;

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
    public function showItemStart()
    {
        $code = $this->getCache()['code'];
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'show clip'), '/showItem ' . $code);

        return $key;
    }

    public function join()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'cancel'), '/join cancel');
        return $key;
    }

    public function competition()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Send a clip'), '/join');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Get Vote Link'), '/voteLink');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Show detailed results'), '/results detail');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Back to main menu'), '/start');

        return $key;
    }

    public function voteItem()
    {
        $code = $this->getCache()['code'];
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'love ' . '❤️❤️'), '/vote 1 ' . $code);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'like ' . '😍'), '/vote 2 ' . $code);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'dislike ' . '😒'), '/vote 3 ' . $code);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'hate ' . '🤢'), '/vote 4 ' . $code);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', '⚠️' . ' report this ' . '⚠️'), '/vote 5 ' . $code);

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
            $limit = count($key) > $page * self::ITEM_PER_PAGE ? $page * self::ITEM_PER_PAGE : count($key) - 1;
            for ($i = ($page - 1) * self::ITEM_PER_PAGE; $i < $limit; $i++) {
                $selectedKeys[] = $key[$i];
            }
            $pagination = $this->createPagination($selectedKeys, $page, $part);
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
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_1', 'Contest Menu') . ' 🏁');
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_1', 'Results') . ' 📊');
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_1', 'Invite Friends') . ' 👬👭');
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_1', 'Guide') . ' 📚');
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_1', 'About Us') . ' 🔖');
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_1', 'Upgrade to premium') . ' 📤');

        $keyboard = [[$key[0]], [$key[1], $key[2]], [$key[3], $key[4]], [$key[5]]];
        $this->setKeyboard([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);
    }
}
