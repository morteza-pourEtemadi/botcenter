<?php

namespace common\components\telegram\commands\botId_1;

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
        $input = $this->getInput();

        if (isset($input[1]) == false) {
            $message = Yii::t('app_1', 'which type of guide you need?');
        } else {
            switch ($input[1]) {
                case 1:
                    $message = Yii::t('app_1', 'prizes');
                    break;
                case 2:
                    $message = Yii::t('app_1', 'how to play');
                    break;
                case 3:
                    $message = Yii::t('app_1', 'upgrading and premium');
                    break;
                default:
                    $this->setPartKeyboard('extra');
                    $this->sendMessage(Yii::t('app_1', 'extra points'));
                    return true;
            }
        }

        $this->setPartKeyboard('guide', 1, 'guide');
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
