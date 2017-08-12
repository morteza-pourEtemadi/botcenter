<?php

namespace common\components\telegram\commands\botId_1;

use yii;
use yii\helpers\Url;
use yii\helpers\Json;
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
    public function guide()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Prizes'), '/help 1');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'How to play'), '/help 2');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Upgrading'), '/help 3');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Extra points'), '/help 4');

        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'back to main menu'), '/start');

        return $key;
    }

    public function extra()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'get extra points'), '/extra');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'back to main menu'), '/start');

        return $key;
    }

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
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Watch clips'), '/showItem');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Get Vote Link'), '/voteLink');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Show detailed results'), '/results detail');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Back to main menu'), '/start');

        return $key;
    }

    public function voteItem()
    {
        $code = $this->getCache()['code'];
        $spec = Json::decode($this->getCache()['spec']);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'love ' . '❤️❤️'), '/vote 1 ' . $code);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'like ' . '😍'), '/vote 2 ' . $code);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'dislike ' . '😒'), '/vote 3 ' . $code);
        if (isset($spec['btn']) == false || $spec['btn']['time'] < time()) {
            $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'hate ' . '🤢'), '/vote 4 ' . $code);
        }
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', '⚠️' . ' report this ' . '⚠️'), '/vote 5 ' . $code);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'skip this clip'), '/showItem');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'back to menu'), '/contestMenu');

        return $key;
    }

    public function whichItem()
    {
        $key = [];
        $ids = $this->getCache()['wci_ids'];
        $cmd = $this->getCache()['cmd'];
        $bck = $this->getCache()['bck'];
        foreach ($ids as $item) {
            $key[] = InlineKeyboardButton::setNewKeyButton($item['caption'], '/' . $cmd . ' ' . $item['id']);
        }
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'back to menu'), '/' . $bck);

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

    public function getCoins()
    {
        $id = $this->getCache()['receipt'];
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Get your Diamonds'), '/buyCoins ' . $id);

        return $key;
    }

    public function getPremium()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Upgrade to premium') . ' 📤', '/upgrade');
        return $key;
    }

    public function premiumPanel()
    {
        $user = User::findOne(['user_id' => $this->_chatId]);
        if ($user->type == User::TYPE_NORMAL) {
            $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Upgrade to premium') . ' 📤', '/upgrade');
        }
        $key[] = InlineKeyboardButton::setNewKeyButton('💎 ' . Yii::t('app_1', 'Buy Diamonds') . ' 💎', '/buyCoins');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Show in the top') . ' 📹⤴️', '/topShow');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'No hate button') . ' 💝', '/specBtn');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Back to main menu'), '/start');

        return $key;
    }

    public function buyCoins()
    {
        $payId = $this->getCache()['payId'];
        $count = $this->getCache()['count'];
        $price = $this->getCache()['price'];
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', '{c} {d} {p} toman', ['c' => $count[0], 'd' => '💎', 'p' => $price[0]]), '', Url::to(['payment/receipt', 'pay_id' => $payId[0]], true));
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', '{c} {d} {p} toman', ['c' => $count[1], 'd' => '💎', 'p' => $price[1]]), '', Url::to(['payment/receipt', 'pay_id' => $payId[1]], true));
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', '{c} {d} {p} toman', ['c' => $count[2], 'd' => '💎', 'p' => $price[2]]), '', Url::to(['payment/receipt', 'pay_id' => $payId[2]], true));
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', '😍😍 «{c} {d} {p} toman» 😍😍', ['c' => $count[3], 'd' => '💎', 'p' => $price[3]]), '', Url::to(['payment/receipt', 'pay_id' => $payId[3]], true));
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'Back to main menu'), '/start');

        return $key;
    }

    public function choosePlans()
    {
        $id = $this->getCache()['id'];
        $cmd = $this->getCache()['cmd'];
        $plans = $this->getCache()['plans'];
        for ($i = 0; $i < 3; $i++) {
            $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', '{d} for {t}', ['d' => $plans[$i]['coin'] . ' 💎', 't' => $this->calcTime($plans[$i]['time'])]), '/' . $cmd . ' ' . $id . ' ' . $i);
        }
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', '😍 {d} for {t} 😍', ['d' => $plans[$i]['coin'] . ' 💎', 't' => $this->calcTime($plans[$i]['time'])]), '/' . $cmd . ' ' . $id . ' ' . $i);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'cancel'), '/premium');

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

        if (count($key) > 5 && $part != 'voteItem') {
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
            case 'comp':
                $keyboard[] = [$selectedKeys[0], $selectedKeys[1]];
                $keyboard[] = [$selectedKeys[2], $selectedKeys[3]];
                $keyboard[] = [$selectedKeys[4]];
                break;
            case 'vote':
                $spec = Json::decode($this->getCache()['spec']);
                if (isset($spec['btn']) == false || $spec['btn']['time'] < time()) {
                    $keyboard[] = [$selectedKeys[0], $selectedKeys[1]];
                    $keyboard[] = [$selectedKeys[2], $selectedKeys[3]];
                    $keyboard[] = [$selectedKeys[4], $selectedKeys[5]];
                    $keyboard[] = [$selectedKeys[6]];
                } else {
                    $keyboard[] = [$selectedKeys[0], $selectedKeys[1]];
                    $keyboard[] = [$selectedKeys[2], $selectedKeys[3]];
                    $keyboard[] = [$selectedKeys[4]];
                    $keyboard[] = [$selectedKeys[5]];
                }

                break;
            case 'guide':
                $keyboard[] = [$selectedKeys[0]];
                $keyboard[] = [$selectedKeys[1], $selectedKeys[2]];
                $keyboard[] = [$selectedKeys[3]];
                $keyboard[] = [$selectedKeys[4]];
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
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_1', 'Contest Menu') . ' 🏁');
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_1', 'Results') . ' 📊');
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_1', 'Invite Friends') . ' 👬👭');
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_1', 'Guide') . ' 📚');
        $key[] = KeyboardButton::setNewKeyButton(Yii::t('app_1', 'About Us') . ' 🔖');
        $key[] = KeyboardButton::setNewKeyButton('👸 ' . Yii::t('app_1', 'VIP Panel') . ' 🤴');

        $keyboard = [[$key[0]], [$key[1], $key[2]], [$key[3], $key[4]], [$key[5]]];
        $this->setKeyboard([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);
    }

    public function beforeExecute()
    {
        $testers = [
            self::CID_MORTEZA_POURETEMADI,
            self::CID_ULTIMATE_ADMIN,
            self::CID_ULTIMATE_ADS_ADMIN,
            self::CID_Z_BANOO,
            self::CID_MOEIN_PROTA,
            self::CID_MAHDI_AMARLOO,
        ];
        parent::beforeExecute();
        if (array_search($this->_chatId, $testers, false)) {
            return false;
        }
        return true;
    }
}
