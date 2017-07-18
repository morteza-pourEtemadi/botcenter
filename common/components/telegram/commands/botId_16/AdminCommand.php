<?php

namespace common\components\telegram\commands\botId_16;

use Yii;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class AdminCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/admin';
    protected $public = false;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $text = explode(' ', $this->_messageText);

        if (isset($text[1]) && $text[1] == 'done') {
            $this->killReply();
        }

        $this->setPartKeyboard('adminsRule');
        $this->sendMessage(Yii::t('app_16', 'admin area'));

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
