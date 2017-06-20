<?php

namespace common\components\telegram\commands\botId_2;

use Yii;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class AboutCommand extends CommandLocal
{
    protected $name = 'about';
    protected $description = 'About us command';
    protected $pattern = '/about';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $message = Yii::t('app_2', 'Ultimate Developers is a programming team to make your world online! Making websites, Telegram Bots and all other web services are in our expertise');
        $message .= "\n\n" . "https://ultimatedevelopers.ir";

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
