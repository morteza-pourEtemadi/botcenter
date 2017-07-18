<?php

namespace common\components\telegram\commands\botId_16;

use Yii;
use common\models\botId_16\Placement;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class CancelPlacementCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/cancelPlacement';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $text = explode(' ', $this->_messageText);
        $placement = Placement::findOne(['id' => $text[1]]);
        $placement->delete();

        $this->killReply();
        $this->setPartKeyboard('main');
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
