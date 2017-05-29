<?php

namespace common\components\telegram\commands\botId_1;

use Yii;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class InviteCommand extends CommandLocal
{
    protected $name = 'invite';
    protected $description = 'command for inviting friends to join the game!';
    protected $pattern = '/invite';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $payload = base64_encode('invite:' . $this->_chatId);
        $url = 'https://tlgrm.me/' . $this->bot->username . '?start=' . $payload;
        $message = Yii::t('app_1', 'did you heard of dubesmash bot? That\'s fun. Come on and join the contest. You may win the great prizes also! Here is the link:');
        $message .= "\n\n" . $url;

        $this->killKeyboard();
        $this->sendMessage($message);
        $this->setMainKeyboard();
        $this->sendMessage(Yii::t('app_1', 'Forward above message to your friends. So they can join the contest via your invitation link!'));
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
