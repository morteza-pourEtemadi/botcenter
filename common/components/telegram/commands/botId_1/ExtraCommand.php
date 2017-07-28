<?php

namespace common\components\telegram\commands\botId_1;

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
        $points += $this->isJoinedChannel('@UD_newsletter') ? 500 : 0;
        $points += $this->isJoinedChannel('@ultimate_developer') ? 1000 : 0;

        if ($points == 0) {
            $this->setPartKeyboard('extra');
            $this->sendMessage(Yii::t('app_1', 'you have not joined any of channels'));
        } else {
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