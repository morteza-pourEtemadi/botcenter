<?php

namespace common\components\telegram\commands\botId_2;

use Yii;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class HelpCommand extends CommandLocal
{
    protected $name = 'help';
    protected $description = 'It\'s some guides for you';
    protected $pattern = '/help';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $message = Yii::t('app_2', 'guide message');
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
