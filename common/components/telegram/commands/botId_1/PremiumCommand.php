<?php

namespace common\components\telegram\commands\botId_1;

use common\models\bot\botId_1\User;
use Yii;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class PremiumCommand extends CommandLocal
{
    protected $name = 'premium';
    protected $description = 'It\'s a command to show premium panel to users';
    protected $pattern = '/premium';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $user = User::findOne(['user_id' => $this->_chatId]);
        $this->setPartKeyboard('premiumPanel');
        $message = Yii::t('app_1', 'Your have {d} diamonds', ['d' => $user->coins]) . "\n\n\n";
        $message .= Yii::t('app_1', 'What can I do for you?');
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
