<?php

namespace common\components\telegram\commands\botId_1;

use Yii;

/**
 * Contest menu command
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class ContestMenuCommand extends CommandLocal
{
    protected $name = 'contestMenu';
    protected $description = 'Contest Menu command';
    protected $pattern = '/contestMenu';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->setPartKeyboard('competition', 1, 'comp');
        $this->sendMessage(Yii::t('app_1', 'What can I do for you?'));
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
