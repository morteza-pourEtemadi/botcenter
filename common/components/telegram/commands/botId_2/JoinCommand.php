<?php

namespace common\components\telegram\commands\botId_2;

use Yii;
use yii\helpers\Json;
use common\models\bot\botId_2\User;
use common\models\bot\botId_2\Khatm;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class JoinCommand extends CommandLocal
{
    protected $name = 'join';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/join';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $input = $this->getInput();

        if (isset($input[1]) && $input[1] == 'cancel') {
            $this->killReply();
            $this->getAllKhatms();
            return true;
        }

        if ($this->isReply) {
            $this->saveData();
        } else {
            if (isset($input[2]) && $input[2] == '1') {
                $this->getNumber($input[1]);
            } elseif (isset($input[1])) {
                $user = User::findOne(['user_id' => $this->_chatId]);
                $ktms = Json::decode($user->current_aya);
                foreach ($ktms as $ktm) {
                    if ($ktm['ktm_id'] == $input[1]) {
                        $this->isJoined($ktm['ktm_id']);
                        return true;
                    }
                }
                $this->showKhatmInfo($input[1]);
            } else {
                $this->getAllKhatms();
            }
        }

        return true;
    }

    public function isJoined($id)
    {
        $this->setCache(['id' => $id]);
        $this->setPartKeyboard('showJoinedKtm');
        $this->sendMessage(Yii::t('app_2', 'You have joined this khatm before.'));
    }

    public function saveData()
    {
        $num = $this->_messageText;
        $user = User::findOne(['user_id' => $this->_chatId]);
        $ktm = Khatm::findOne(['id' => $this->getReply()['ktm_id']]);

        if (is_numeric($num) == false) {
            $num = $this->convertNPTE($num);
        }

        if (is_numeric($num)) {
            switch ($ktm->type) {
                case Khatm::TYPE_AYA:
                    if ($num < 5) {
                        $this->sendMessage(Yii::t('app_2', 'minimum number of aya is 5. please enter a number greater than or equal to 5'));
                        return false;
                    }
                    if ($num > 25) {
                        $this->sendMessage(Yii::t('app_2', 'maximum number of ayat is 25. please enter a number fewer than or equal to 25'));
                        return false;
                    }
                    if ($ktm->current_pointer + $num > 6236) {
                        $this->sendMessage(Yii::t('app_2', 'There is no such as aya in this khatm'));
                        return false;
                    }
                    break;
                case Khatm::TYPE_PAGE:
                    if ($num < 1) {
                        $this->sendMessage(Yii::t('app_2', 'minimum number of pages is 1. please enter a number greater than or equal to 1'));
                        return false;
                    }
                    if ($ktm->current_pointer + $num > 604) {
                        $this->sendMessage(Yii::t('app_2', 'There is no such as page in this khatm'));
                        return false;
                    }
                    break;
                case Khatm::TYPE_JOZ:
                    if ($num < 1) {
                        $this->sendMessage(Yii::t('app_2', 'minimum number of Joz`s is 1. please enter a number greater than or equal to 1'));
                        return false;
                    }
                    if ($ktm->current_pointer + $num > 30) {
                        $this->sendMessage(Yii::t('app_2', 'There is no such as joz in this khatm'));
                        return false;
                    }
                    break;
            }
            $current = Json::decode($user->current_aya);
            $current[] = [
                'ktm_id' => $ktm->id,
                'xpd' => $num,
                'rtd' => 0,
                'cp' => $ktm->current_pointer,
                'lup' => time(),
            ];
            $user->current_aya = Json::encode($current);
            $user->save();
            $ktm->current_pointer += $num;
            $ktm->save();

            $this->killReply();
            $this->killKeyboard();
            $this->sendMessage(Yii::t('app_2', 'You have added to the khatm'));

            $this->setCache(['x' => $ktm->getTypePart(), 'id' => $ktm->id]);
            $this->setPartKeyboard('ktmMenu', 1, 'ktm');
            $this->sendMessage(Yii::t('app_2', 'Can I help you?'));
        } else {
            $this->sendMessage(Yii::t('app_2', 'Just send numbers'));
        }

        return true;
    }

    public function getAllKhatms()
    {
        $i = 0;
        $ids = [];
        $ktm = Khatm::findAll(['status' => Khatm::STATUS_ACTIVE]);
        foreach ($ktm as $item) {
            $ids[$i]['id'] = $item->id;
            $ids[$i]['title'] = $item->title;
            $i++;
        }
        $this->setCache(['ktm_ids' => $ids]);
        $this->setPartKeyboard('getKhatms');
        $this->sendMessage(Yii::t('app_2', 'Select one of khatms to get its information. *your selection does not mean koining*'));
    }

    public function showKhatmInfo($input)
    {
        $ktm = Khatm::findOne(['id' => $input]);

        $message = Yii::t('app_2', 'Khatm`s number ') . $ktm->number . "\n\n";
        $message .= Yii::t('app_2', 'Khatm`s purpose: ') . $ktm->title . "\n";
        $message .= Yii::t('app_2', 'Khatm`s type: ') . $ktm->getType() . "\n";
        $message .= Yii::t('app_2', 'current reads till now: ') . $ktm->current_pointer . "\n";
        $message .= Yii::t('app_2', 'age of khatm: ') . ' ' . $this->calcTime(time() - $ktm->created_at) . "\n\n";
        $message .= Yii::t('app_2', 'Are you joining this khatm?');

        $this->setCache(['ktm_id' => $input]);
        $this->setPartKeyboard('joinKhatm');
        $this->sendMessage($message);
    }

    public function getNumber($input)
    {
        $ktm = Khatm::findOne(['id' => $input]);
        $message = Yii::t('app_2', 'Enter how many {x} you want to read daily?', ['x' => $ktm->getTypePart()]);

        $this->setReply(['joinNum' => 1, 'ktm_id' => $ktm->id]);
        $this->setPartKeyboard('enterNum');
        $this->sendMessage($message);
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->description;
    }
}
