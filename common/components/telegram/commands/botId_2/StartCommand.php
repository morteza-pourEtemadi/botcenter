<?php

namespace common\components\telegram\commands\botId_2;

use Yii;
use common\models\bot\Subscribers;
use common\models\bot\botId_2\User;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class StartCommand extends CommandLocal
{
    protected $name = 'start';
    protected $description = 'Start the bot';
    protected $pattern = '/start';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        if (($oldUser = User::findOne(['user_id' => $this->_chatId])) == false) {
            $user = new User([
                'user_id' => $this->_chatId,
            ]);
            if ($this->isUserOwner()) {
                $user->type = User::TYPE_OWNER;
            }
            $user->save();
            $user->getUniqueUser()->setSettings('language', 1);
            Yii::$app->language = 'fa-IR';

            $subscriber = new Subscribers([
                'user_id' => $this->_chatId,
                'bot_id' => $this->bot->bot_id,
            ]);
            $subscriber->save();
        }

        if ($this->isJoinedChannel() == false) {
            $message = Yii::t('app_2', 'Please join our channel to be noticed of news and upcoming.');
            $this->killKeyboard();
            $this->sendMessage($message);
            return true;
        }

        $this->setMainKeyboard();
        $this->sendMessage(Yii::t('app_2', 'welcome'));
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
