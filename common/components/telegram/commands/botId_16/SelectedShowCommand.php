<?php

namespace common\components\telegram\commands\botId_16;

use common\models\botId_16\Shows;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class SelectedShowCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/selectedShow';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $text = explode(' ', $this->_messageText);
        if (isset($text[1]) === false) {
            return false;
        }

        if ($recipe = Shows::findOne(['id' => $text[1]])) {
            $this->killKeyboard();
            $attachments = $this->getAttachment($recipe);
            foreach ($attachments as $attachment) {
                if (isset($attachment['from_chat_id'])) {
                    $this->api->forwardMessage($this->_chatId, $attachment['from_chat_id'], $attachment['message_id']);
                } else {
                    $send = $this->sendFile($attachment[0], Html::decode($recipe->title));
                    if ($send->ok === false && $send->description = 'Bad Request: Wrong persistent file_id specified: Wrong string length') {
                        $send = $this->sendFile($attachment, Html::decode($recipe->title));
                    }
                }
            }
            $this->setPartKeyboard('showRecipe');
            $this->sendMessage(Html::decode($recipe->title) . "\n" . Html::decode($recipe->text));

            return true;
        }

        return false;
    }

    public function getAttachment($recipe)
    {
        if ($recipe->attachments !== null) {
            return Json::decode($recipe->attachments);
        } else {
            return [];
        }
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return Yii::t('app', 'It\'s a sample command');
    }
}
