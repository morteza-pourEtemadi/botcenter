<?php

namespace common\components\telegram\commands\botId_16;

use common\models\botId_16\Shows;
use Yii;
use yii\helpers\Json;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class NewShowCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/newShow';
    protected $public = false;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $text = explode(' ', $this->_messageText);

        if ($this->isReply === false) {
            $recipe = new Shows([
                'create_time' => time()
            ]);
            $recipe->save(false);
            $this->killReply();
            $this->setReply(['id' => $recipe->id, 'recipe_title' => 1]);
            $this->page = $recipe->id;
            $this->setPartKeyboard('cancelRecipe');
            $this->sendMessage(Yii::t('app_16', 'shows now enter your recipe title. (required)'));
        } else {
            $recipe = Shows::findOne(['id' => $this->getReply()['id']]);

            if (isset($this->getReply()['recipe_attachments'])) {
                if (isset($this->update->message->forward_from->id) || isset($this->update->message->forward_date)) {
                    $this->addForwardAttachment($recipe);
                } else {
                    $this->addFileAttachment($recipe);
                }
                $recipe->save(false);
                $this->shows = $recipe;
                $this->setPartKeyboard('addAttachments');
                $this->sendMessage(Yii::t('app_16', 'shows now enter your recipe attachments. (optional)'));
            } elseif (isset($this->getReply()['recipe_text'])) {
                if (isset($this->update->message->forward_from->id) || isset($this->update->message->forward_date)) {
                    $attachment[0] = [
                        'from_chat_id' => $this->update->message->chat->id,
                        'message_id' => $this->update->message->message_id,
                    ];
                    $recipe->text = '';
                    $recipe->attachments = Json::encode($attachment);
                } else {
                    $recipe->text = $this->_messageText;
                }
                $recipe->save(false);
                $this->killReply();
                $this->setReply(['recipe_attachments' => 1, 'id' => $recipe->id]);
                $this->page = $recipe->id;
                $this->setPartKeyboard('addAttachments');
                $this->sendMessage(Yii::t('app_16', 'shows now enter your recipe attachments. (optional)'));
            } elseif (isset($this->getReply()['recipe_title'])) {
                $recipe->title = $this->_messageText;
                $recipe->save(false);
                $this->killReply();
                $this->setReply(['recipe_text' => 1, 'id' => $recipe->id]);
                $this->page = $recipe->id;
                $this->setPartKeyboard('cancelRecipe');
                $this->sendMessage(Yii::t('app_16', 'shows now enter your recipe text. (required)'));
            }
        }
    }

    public function getAttachments()
    {
        $message = $this->update->message;
        if ($fileId = $message->getFileId()) {
            return $fileId;
        }
        return '';
    }

    public function addForwardAttachment($recipe)
    {
        $attachments = [];
        if ($recipe->attachments !== null) {
            $attachments = Json::decode($recipe->attachments);
        }
        $attachments[] = [
            'from_chat_id' => $this->update->message->chat->id,
            'message_id' => $this->update->message->message_id,
        ];
        $recipe->attachments = Json::encode($attachments);
    }

    public function addFileAttachment($recipe)
    {
        if (isset($this->update->message->text) === false) {
            $attachment = $this->getAttachments();
            if ($attachment !== '') {
                $attachments = [];
                if ($recipe->attachments !== null) {
                    $attachments = Json::decode($recipe->attachments);
                }
                $attachments[] = $attachment;
                $recipe->attachments = Json::encode($attachments);
            }
        } else {
            $this->sendMessage(Yii::t('app_16', 'just attachments'));
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
