<?php

namespace common\components\telegram\commands\botId_16;

use Yii;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class SelectShowCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/selectShow';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->setPartKeyboard('getRecipes');
        $this->sendMessage(Yii::t('app_16', 'select a show'));
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return Yii::t('app', 'It\'s a sample command');
    }
}
