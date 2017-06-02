<?php

namespace common\components\telegram\commands\botId_1;

use common\models\bot\botId_1\User;
use common\models\bot\Receipt;
use Yii;
use yii\helpers\Json;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class BuyCoinsCommand extends CommandLocal
{
    protected $name = 'buyCoins';
    protected $description = 'It\'s a command to buy diamonds';
    protected $pattern = '/buyCoins';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $input = explode(' ', $this->_messageText);
        $priceString = Json::decode($this->bot->priceString);
        if (isset($input[1])) {
            $user = User::findOne(['user_id' => $this->_chatId]);
            $receipt = Receipt::findOne(['id' => $input[1]]);
            if ($receipt->user_id == $user->user_id) {
                $product = (integer) explode(':', $receipt->product)[1];
                $coin = $priceString['objects']['coins'][$product];
                $user->coins += $coin['count'];
                $user->save();

                $receipt->status = Receipt::STATUS_PAYED_USED;
                $receipt->save();

                $this->setMainKeyboard();
                $this->sendMessage(Yii::t('app_1', '{c} Diamonds are added to your account successfully. Enjoy them!', ['c' => $coin['count']]));
            } else {
                $this->setMainKeyboard();
                $this->sendMessage(Yii::t('app_1', 'Your account and the one which requested the diamonds is not same. Please pay more attention'));
            }
        } else {
            $payId = $price = $count = [];
            $priceString = Json::decode($this->bot->priceString);
            for ($i = 0; $i < 4; $i++) {
                $coin = $priceString['objects']['coins'][$i];
                $price[] = $coin['price'];
                $count[] = $coin['count'];
                $description = Yii::t('app_1', 'Buy {c} diamonds', ['c' => $count[$i]]);
                $payId[] = implode(
                    '.ubpd.',
                    [
                        base64_encode($this->_chatId),
                        base64_encode($this->bot->bot_id),
                        base64_encode($price[$i]),
                        base64_encode(0),
                        base64_encode($description),
                        base64_encode('coin:' . $i),
                    ]
                );
            }

            $this->setCache(['payId' => $payId, 'count' => $count, 'price' => $price]);
            $this->setPartKeyboard('buyCoins');
            $this->sendMessage(Yii::t('app_1', 'Choose one of offers:'));
        }
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
