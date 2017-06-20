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
class CountCommand extends CommandLocal
{
    protected $name = 'count';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/count';
    protected $public = false;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $todayMid = time() - ((time() - 1497904200) % 86400);
        $users = User::findAll([1]);
        $tUsers = [];

        foreach ($users as $user) {
            if ($user->created_at >= $todayMid) {
                $tUsers[] = $user;
            }
        }

        $aKhatms = Khatm::findAll(['status' => Khatm::STATUS_ACTIVE]);
        $fKhatms = Khatm::findAll(['status' => Khatm::STATUS_FINISHED]);

        $message = Yii::t('app_2', 'Number of whole users: ') . count($users) . "\n";
        $message.= Yii::t('app_2', 'Number of today users: ') . count($tUsers) . "\n\n";
        $message.= Yii::t('app_2', 'Number of active khatms: ') . count($aKhatms) . "\n";
        $message.= Yii::t('app_2', 'Number of finished khatms: ') . count($fKhatms);

        $this->setMainKeyboard();
        $this->sendMessage($message);
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
