<?php

namespace common\components\telegram\commands\botId_16;

use Yii;
use common\models\botId_16\Shows;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class CancelShowCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/cancelShow';
    protected $public = false;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $text = explode(' ', $this->_messageText);
        $show = Shows::findOne(['id' => $text[1]]);
        $show->delete();

        $this->killReply();
        $this->setPartKeyboard('adminsRule');
        $this->sendMessage(Yii::t('app_16', 'canceled'));
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return Yii::t('app', 'It\'s a sample command');
    }
}
