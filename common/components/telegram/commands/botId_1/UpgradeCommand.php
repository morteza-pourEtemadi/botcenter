<?php

namespace common\components\telegram\commands\botId_1;

use common\models\bot\Receipt;
use Yii;
use yii\helpers\Json;
use common\models\bot\botId_1\User;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class UpgradeCommand extends CommandLocal
{
    protected $name = 'upgrade';
    protected $description = 'It\'s a command to upgrade to premium';
    protected $pattern = '/upgrade';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $input = explode(' ', $this->_messageText);
        $user = User::findOne(['user_id' => $this->_chatId]);
        if (isset($input[1])) {
            $receipt = Receipt::findOne(['id' => $input[1]]);
            if ($receipt->user_id == $this->_chatId) {
                $user->type = User::TYPE_PREMIUM;
                $user->coins = 10;
                $user->save();

                $receipt->status = Receipt::STATUS_PAYED_USED;
                $receipt->save();

                $this->setMainKeyboard();
                $this->sendMessage(Yii::t('app_1', 'You have successfully upgraded to premium user. You are rewarded 10 coins for joining the league! enjoy...'));
            } else {
                $this->setMainKeyboard();
                $this->sendMessage(Yii::t('app_1', 'Your account and one which requested the upgrade is not same. Please pay more attention'));
            }
            return true;
        }

        if ($user->type == User::TYPE_PREMIUM) {
            return false;
        }
        $priceString = Json::decode($this->bot->priceString);
        $price = $priceString['objects']['upgrade'];
        $description = Yii::t('app_1', 'Upgrade to premium to get more tickets to win the prize!!');
        $payId = implode(
            '.ubpd.',
            [
                base64_encode($this->_chatId),
                base64_encode($this->bot->bot_id),
                base64_encode($price),
                base64_encode(0),
                base64_encode($description),
                base64_encode('upgrade'),
            ]
        );
        $this->setCache(['payId' => $payId, 'price' => $price]);
        $this->setPartKeyboard('upgrade');
        $this->sendMessage(Yii::t('app_1', 'Upgrading to premium lets you to send 6 clips instead of 2. So you have more chance to win. Also you can buy coins and speed up your score!'));
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->description;
    }
}
