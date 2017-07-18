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
class StartCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/start';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        if ($user = User::findOne(['user_id' => $this->_chatId])) {
            $this->setPartKeyboard('main');
            $this->sendMessage(Yii::t('app_16', 'welcome message'));
        } else {
            $user = new User([
                'user_id' => $this->_chatId,
                'status' => User::STATUS_ACTIVE,
                'type' => User::TYPE_PARTICIPANT,
                'create_time' => time(),
                'survey' => '',
            ]);
            $user->save(false);
            $this->setPartKeyboard('main');
            $this->sendMessage(Yii::t('app_16', 'welcome message'));
        }

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
