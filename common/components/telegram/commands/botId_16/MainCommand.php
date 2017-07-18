<?php

namespace common\components\telegram\commands\botId_16;

use Yii;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class MainCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/main';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->setPartKeyboard('main');
        $this->sendMessage(Yii::t('app_16', 'welcome message'));
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return Yii::t('app', 'It\'s a sample command');
    }
}
