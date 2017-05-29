<?php

namespace common\components\telegram\commands\botId_1;

use yii;
use yii\helpers\Url;
use common\models\bot\botId_1\User;
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

    public function whichItem()
    {
        $key = [];
        $ids = $this->getCache()['wci_ids'];
        foreach ($ids as $item) {
            $key[] = InlineKeyboardButton::setNewKeyButton($item['caption'], '/voteLink ' . $item['id']);
        }
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'back to menu'), '/contestMenu');

        return $key;
    }

    public function upgrade()
    {
        $price = $this->getCache()['price'];
        $payId = $this->getCache()['payId'];
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Pay {price} Toman for upgrading.', ['price' => $price]), '', Url::to(['payment/receipt', 'pay_id' => $payId], true));
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'back to main menu'), '/start');

        return $key;
    }

    public function getUpgrade()
    {
        $id = $this->getCache()['receipt'];
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Finish Upgrade Process'), '/upgrade ' . $id);

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
        $user = User::findOne(['user_id' => $this->_chatId]);

        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_1', 'Contest Menu') . ' 🏁');
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_1', 'Results') . ' 📊');
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_1', 'Invite Friends') . ' 👬👭');
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_1', 'Guide') . ' 📚');
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_1', 'About Us') . ' 🔖');
        if ($user->type == User::TYPE_NORMAL) {
            $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_1', 'Upgrade to premium') . ' 📤');
        } else {
            $key[] = KeyboardButton::setNewKeyButton('👸 ' . Yii::t('app_1', 'Premium Panel') . ' 🤴');
        }

        $keyboard = [[$key[0]], [$key[1], $key[2]], [$key[3], $key[4]], [$key[5]]];
        $this->setKeyboard([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);
    }
}
