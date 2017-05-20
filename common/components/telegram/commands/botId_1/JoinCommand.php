<?php

namespace common\components\telegram\commands\botId_1;

use common\models\bot\botId_1\User;
use common\models\bot\botId_1\X;
use Yii;

/**
 * Join Command is for joining the contest
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class JoinCommand extends CommandLocal
{
    protected $name = 'join';
    protected $description = 'Send your items and Join the contest';
    protected $pattern = '/join';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $input = explode(' ', $this->_messageText);
        if (isset($input[1]) && $input[1] == 'cancel') {
            $this->killReply();
            $this->setPartKeyboard('competition');
            $this->sendMessage(Yii::t('app_1', 'What can I do for you?'));

            return true;
        }

        $user = User::findOne(['user_id' => $this->_chatId]);
        if ($this->isReply == false) {
            if (($user->type == User::TYPE_NORMAL && $user->XsNo >= 2) || ($user->type == User::TYPE_PREMIUM && $user->XsNo >= 6)) {
                $this->setPartKeyboard('competition');
                $this->sendMessage(Yii::t('app_1', "You got to your limits of sending file. 
                If you are not a premium user, you can send more files with being premium; 
                And if you are already a premium user, it`s the end.\nPlease read the guides for more information"));
                return false;
            }

            $this->setReply(['join' => 1]);
            $this->setPartKeyboard('join');
            $this->sendMessage(Yii::t('app_1', 'Please send a dubsmash clip to join the contest. Please notice that sent «File» must be a video!'));
            return true;
        } else {
            if ($this->update->message->isVideo() == false) {
                $this->sendMessage(Yii::t('app_1', 'Please just send a Video. Pay attention to your file`s format.'));
                return true;
            }
            // Creating new Item
            $item = new X([
                'creator_id' => $this->_chatId,
                'file_id' => $this->update->message->getFileId(),
            ]);
            $item->save();
            // Update User Info
            $user->XsNo += 1;
            $user->save();

            $this->killKeyboard();
            $this->sendMessage(Yii::t('app_1', 'Your clip is saved. Now it is participated in competition.'));
            usleep(750000);

            $url = 'https://tlgrm.me/' . $this->bot->username . '?start=' . $item->code;
            $message = Yii::t('app_1', "Please vote for my clip. It`s an exciting competition with great prizes\n\nClick the following link to watch my dubsmash and vote for it\n");
            $message .= $url;
            $this->sendMessage($message);
            usleep(1750000);

            $this->setPartKeyboard('competition');
            $this->sendMessage(Yii::t('app_1', 'Forward above message to your friends. So they can vote for your clip'));
        }

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
