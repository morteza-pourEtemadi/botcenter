<?php

namespace common\components\telegram\commands\botId_1;

use common\models\bot\botId_1\User;
use common\models\bot\botId_1\X;
use Yii;
use yii\helpers\Json;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class SpecBtnCommand extends CommandLocal
{
    protected $name = 'specBtn';
    protected $description = 'It\'s command for showing items without hate btn';
    protected $pattern = '/specBtn';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $input = explode(' ', $this->_messageText);
        if (isset($input[1])) {
            if (isset($input[2])) {
                $user = User::findOne(['user_id' => $this->_chatId]);
                $item = X::findOne(['id' => $input[1]]);
                $plan = $this->getPlans()[(integer)$input[2]];

                if ($user->coins > $plan['coin']) {
                    $user->coins -= $plan['coin'];
                    $user->save();

                    $spec = Json::decode($item->specialOptions);
                    $spec['btn']['time'] = (isset($spec['btn']) ? $spec['btn']['time'] : time())+ $plan['time'];
                    $item->specialOptions = Json::encode($spec);
                    $item->save();

                    $this->setPartKeyboard('premiumPanel');
                    $this->sendMessage(Yii::t('app_1', 'Now your clip has no hate button'));
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
                    $this->sendMessage(Yii::t('app_1', 'You have not enough diamonds. You need to buy some. Please choose one of offers:'));
                }
            } else {
                $this->setCache(['plans' => $this->getPlans(), 'id' => $input[1], 'cmd' => 'specBtn']);
                $this->setPartKeyboard('choosePlans');
                $this->sendMessage(Yii::t('app_1', 'Choose a plan:'));
            }
        } else {
            $i = 0;
            $ids = [];
            $items = X::findAll(['creator_id' => $this->_chatId]);
            foreach ($items as $item) {
                $ids[$i]['id'] = $item->id;
                $ids[$i]['caption'] = $item->caption != '' ? $item->caption : Yii::t('app_1', 'No Caption');
                $i++;
            }
            $this->setCache(['wci_ids' => $ids, 'cmd' => 'specBtn', 'bck' => 'premium']);
            $this->setPartKeyboard('whichItem');
            $this->sendMessage(Yii::t('app_1', 'which item you wanna consecrate its buttons?'));
        }

        return true;
    }

    public function getPlans()
    {
        $plans = [
            0 => [
                'time' => 43200,
                'coin' => 40
            ],
            1 => [
                'time' => 86400,
                'coin' => 80
            ],
            2 => [
                'time' => 259200,
                'coin' => 240
            ],
            3 => [
                'time' => 604800,
                'coin' => 510
            ],
        ];

        return $plans;
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->description;
    }
}
