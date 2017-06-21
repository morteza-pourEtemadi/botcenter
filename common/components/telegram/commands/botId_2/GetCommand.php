<?php

namespace common\components\telegram\commands\botId_2;

use Yii;
use yii\helpers\Json;
use common\models\bot\botId_2\User;
use common\models\bot\botId_2\Quran;
use common\models\bot\botId_2\Khatm;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class GetCommand extends CommandLocal
{
    protected $name = 'get';
    protected $description = 'It\'s a command to get aya/page/joz';
    protected $pattern = '/get';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $input = $this->getInput();
        if (isset($input[2]) == false) {
            return false;
        }

        $current = $this->getCurrent($input[2]);
        $ktm = Khatm::findOne(['id' => $input[2]]);
        $this->setCache(['x' => $ktm->getTypePart(), 'id' => $ktm->id]);

        if ($input[1] == 'dismiss') {
            $this->setCache(['id' => $input[2]]);
            $this->setPartKeyboard('dismiss');
            $this->sendMessage(Yii::t('app_2', 'you must read your last share'));
            return true;
        } elseif ($input[1] == 'dismissF') {
            $this->decideWhich($ktm->type, $current['cp'], $current['xpd']);
            $this->deleteCurrent($input[2]);

            $this->setMainKeyboard();
            $this->sendMessage(Yii::t('app_2', 'the khatm dismissed'));
            return true;
        } elseif ($input[1] == 'rms') {
            if ($ktm->status == Khatm::STATUS_FINISHED) {
                $this->deleteCurrent($input[2]);
                $this->setMainKeyboard();
                $this->sendMessage(Yii::t('app_2', 'This khatm is finished thanks to God. Please join another khatm.'));
                return true;
            }
            $this->setCurrent($input[2], $current['cp'] + $current['xpd'], 0);
            $current = $this->getCurrent($input[2]);
            $ktm->current_pointer += $current['xpd'];
            $ktm->save();
        }

        $this->decideWhich($ktm->type, $current['cp'], $current['xpd']);
        return true;
    }

    public function deleteCurrent($id)
    {
        $user = User::findOne(['user_id' => $this->_chatId]);
        $currents = Json::decode($user->current_aya);

        $key = 0;
        foreach ($currents as $key => $value) {
            if ($value['ktm_id'] == $id) {
                break;
            }
        }
        unset($currents[$key]);
        $user->current_aya = Json::encode($currents);
        return $user->save();
    }

    public function getCurrent($id)
    {
        $user = User::findOne(['user_id' => $this->_chatId]);
        $currents = Json::decode($user->current_aya);

        $key = 0;
        foreach ($currents as $key => $value) {
            if ($value['ktm_id'] == $id) {
                break;
            }
        }
        $current = $currents[$key];
        return $current;
    }

    public function setCurrent($id, $cp, $rtd)
    {
        $user = User::findOne(['user_id' => $this->_chatId]);

        $currents = Json::decode($user->current_aya);
        foreach ($currents as $key => $value) {
            if ($value['ktm_id'] == $id) {
                $value['cp'] = $cp;
                $value['rtd'] = $rtd;
                $value['lup'] = time();
                break;
            }
        }
        $user->current_aya = Json::encode($currents);
        return $user->save();
    }

    public function decideWhich($type, $offset, $limit)
    {
        switch ($type) {
            case Khatm::TYPE_AYA:
                $this->sendAyat($offset, $limit);
                break;
            case Khatm::TYPE_PAGE:
                $this->sendPage($offset, $limit);
                break;
            case Khatm::TYPE_JOZ:
                $this->sendJoz($offset, $limit);
                break;
        }
    }

    public function sendAyat($offset, $limit)
    {
        if ($offset + $limit > 6236) {
            $limit = 6236 - $offset;
            $this->endKtm();
        }

        $aya = [];
        for ($i = 0; $i <= $limit; $i++) {
            $aya[] = Quran::findOne(['index' => ($offset + $i)]);
        }

        $this->sendShare($aya, $limit);
    }

    public function sendPage($offset, $limit)
    {
        if ($offset + $limit > 604) {
            $limit = 604 - $offset;
            $this->endKtm();
        }
        $aya = [];
        for ($i = 0; $i < $limit; $i++) {
            $thisPage = Quran::findAll(['page' => ($offset + $i)]);
            array_merge($aya, $thisPage);
        }
        $aya[] = Quran::findOne(['page' => $limit]);

        $this->sendShare($aya, $limit);
    }

    public function sendJoz($offset, $limit)
    {
        if ($offset + $limit > 30) {
            $limit = 30 - $offset;
            $this->endKtm();
        }
        $message = Yii::t('app_2', 'your share is: ') . "\n\n";
        for ($i = 0; $i < $limit; $i++) {
            $message .= Yii::t('app_2', 'Joz ') . ($offset + $i) . "\n";
        }
        $this->killKeyboard();
        $this->sendMessage($message);

        $this->setPartKeyboard('ktmMenu');
        $this->sendMessage(Yii::t('app_2', 'Sorry. But we can`t send a whole joz. please read from a book'));
    }

    public function sendShare($aya, $limit)
    {
        $j = 0;
        $summery[$j]['sura'] = $aya[0]->sura;
        $summery[$j]['begin'] = $aya[0]->aya;

        for ($i = 0; $i < $limit - 1; $i++) {
            if ($aya[$i]->sura !== $aya[$i + 1]->sura) {
                $summery[$j]['end'] = $aya[$i]->aya;
                $j++;
                $summery[$j]['sura'] = $aya[$i + 1]->sura;
                $summery[$j]['begin'] = $aya[$i + 1]->aya;
            }
        }
        $summery[$j]['end'] = $aya[$i]->aya;

        $j = 0;
        $sm = Yii::t('app_2', 'Today Ayat:') . "\n";
        while (isset($summery[$j])) {
            $sm .= Yii::t('app_2', 'Holy sura') . ' ' . $summery[$j]['sura'] . ' ' . Yii::t('app_2', 'from') . ' ' . $summery[$j]['begin'] . ' ' . Yii::t('app_2', 'to') . ' ' . $summery[$j]['end'] . "\n";
            $j++;
        }

        unset($aya[$limit]);
        $this->killKeyboard();
        $this->sendMessage($sm);
        $this->sendMessage(Yii::t('app_2', 'aoozo b Allah'));
        $this->sendMessage(Yii::t('app_2', 'besme Allah'));

        foreach ($aya as $item) {
            $message = $item->text . "\n\n";
            $message.= $item->translation;
            $this->sendMessage($message);
            usleep(100000);
        }
        $input = $this->getInput();
        if ($input[1] != 'dismissF') {
            $this->setPartKeyboard('ktmMenu', 1, 'ktm');
        }
        $this->sendMessage(Yii::t('app_2', 'Please read your ayat!'));
    }

    public function endKtm()
    {
        $input = $this->getInput();
        $ktm = Khatm::findOne(['id' => $input[2]]);
        $ktm->status = Khatm::STATUS_FINISHED;
        $ktm->save();
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->description;
    }
}
