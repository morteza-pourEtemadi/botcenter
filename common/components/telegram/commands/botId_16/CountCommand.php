<?php

namespace common\components\telegram\commands\botId_16;

use common\models\botId_16\User;
use Yii;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class CountCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/count';
    protected $public = false;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $exhibitors = User::findAll(['type' => User::TYPE_EXHIBITOR]);
        $participants = User::findAll(['type' => User::TYPE_PARTICIPANT]);

        $message = Yii::t('app_16', 'count of exhibitors: ') . count($exhibitors) . "\n";
        $message .= Yii::t('app_16', 'count of participants: ') . count($participants) . "\n";

        $this->sendMessage($message);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return Yii::t('app', 'It\'s a sample command');
    }
}
