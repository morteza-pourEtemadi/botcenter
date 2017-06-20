<?php

namespace common\components\telegram\commands\botId_2;

use common\models\bot\botId_2\Khatm;
use common\models\bot\botId_2\User;
use Yii;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class AddKhatmCommand extends CommandLocal
{
    protected $name = 'addKhatm';
    protected $description = 'It\'s a command to add new khatm';
    protected $pattern = '/addKhatm';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $user = User::findOne(['user_id' => $this->_chatId]);
        if ($user->type == User::TYPE_NORMAL) {
            $this->sendMessage(Yii::t('app_2', 'You have no access to do changes here!'));
            return false;
        }

        $input = explode(' ', $this->_messageText);
        if (isset($input[1]) && $input[1] == 'cancel') {
            $this->killReply();
            $this->setMainKeyboard();
            $this->sendMessage(Yii::t('app_2', 'Adding new Khatm is canceled'));
            return true;
        }

        if ($this->isReply) {
            if ($this->getReply()['addKhatm'] == 1) {
                $this->killReply();
                $this->setReply(['addKhatm' => 2, 'title' => $this->_messageText]);

                $this->setPartKeyboard('khatmTypes');
                $this->sendMessage(Yii::t('app_2', 'Now select type of the khatm'));
            } elseif ($this->getReply()['addKhatm'] == 2) {
                $this->saveData($this->getReply()['title'], $input[1]);

                $this->killReply();
                $this->setMainKeyboard();
                $this->sendMessage(Yii::t('app_2', 'Your khatm is saved successfully'));
            }
        } else {
            $this->setReply(['addKhatm' => 1]);
            $this->setPartKeyboard('addKhatm');
            $this->sendMessage(Yii::t('app_2', 'Please enter the khatm`s title(purpose):'));
        }

        return true;
    }

    public function saveData($title, $type)
    {
        $abortedKhatms = Khatm::findAll(['status' => Khatm::STATUS_ABORTED]);
        $notAbortedKhatms = count(Khatm::find()->all()) - count($abortedKhatms);
        $number = $notAbortedKhatms + 1;

        $khatm = new Khatm([
            'number' => $number,
            'current_pointer' => 1,
            'title' => $title,
            'type' => $type,
            'created_at' => time(),
        ]);
        $khatm->save();
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->description;
    }
}
