<?php

namespace common\components\telegram\commands\botId_2;

use common\models\bot\botId_2\Khatm;
use Yii;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class KtmMenuCommand extends CommandLocal
{
    protected $name = 'ktmMenu';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/ktmMenu';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $input = $this->getInput();
        $ktm = Khatm::findOne(['id' => $input[1]]);

        $this->setCache(['x' => $ktm->getTypePart(), 'id' => $input[1]]);
        $this->setPartKeyboard('ktmMenu');
        $this->sendMessage(Yii::t('app_2', 'Can I help you?'));

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
