<?php

namespace common\components\telegram\commands\botId_1;

use common\models\bot\botId_1\User;
use Yii;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class ExtraCommand extends CommandLocal
{
    protected $name = 'extra';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/extra';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $points = 0;
        $user = User::findOne(['user_id' => $this->_chatId]);
        if ($user->extra == 0) {
            $points += $this->isJoinedChannel('@UD_newsletter') ? 500 : 0;
            $points += $this->isJoinedChannel('@ultimate_developer') ? 1000 : 0;
        } elseif ($user->extra == 500) {
            $points += $this->isJoinedChannel('@ultimate_developer') ? 1000 : 0;
        } elseif ($user->extra == 1000) {
            $points += $this->isJoinedChannel('@UD_newsletter') ? 500 : 0;
        }

        if ($points == 0) {
            $this->setPartKeyboard('extra');
            $this->sendMessage(Yii::t('app_1', 'you have not joined any of channels'));
        } else {
            $user->extra += $points;
            $user->save();
            $this->killKeyboard();
            $this->sendMessage(Yii::t('app_1', 'Congratulations! you earned {p} points.', ['p' => $points]));

            $this->setPartKeyboard('guide');
            $this->sendMessage(Yii::t('app_1', 'which type of guide you need?'));
        }

    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->description;
    }
}
