<?php

namespace common\components\telegram\commands\botId_16;

use Yii;
use common\models\botId_16\Leads;
use common\traits\TelegramCommandTrait;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class NewLeadCommand extends CommandLocal
{
    use TelegramCommandTrait;

    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/newLead';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $text = explode(' ', $this->_messageText);

        if ($this->isReply === false) {
            if (isset($text[2]) == false) {
                return false;
            }
            $lead = new Leads([
                'category' => $text[1],
                'subcategory' => $text[2],
                'create_time' => time(),
                'creator_id' => $this->_chatId,
                'status' => Leads::STATUS_WRITING,
            ]);
            $lead->save(false);
            $this->setReply(['id' => $lead->id, 'text' => 1]);
            $this->page = $lead->id;
            $this->setPartKeyboard('cancelLead');
            $this->sendMessage(Yii::t('app_16', 'now enter your lead text'));
        } else {
            $lead = Leads::findOne(['id' => $this->getReply()['id']]);
            if (isset($this->getReply()['text'])) {
                if ($this->checkInputMessage($this->_messageText) == false) {
                    return false;
                }
                $lead->text = $this->_messageText;
                $lead->status = Leads::STATUS_PENDING;
                $lead->save(false);

                $this->api->sendMessage(101538817, Yii::t('app_16', 'new placement to confirm'));

                $this->killReply();
                $this->setPartKeyboard('main');
                $this->sendMessage(Yii::t('app_16', 'your placement is pending to approve'));
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return Yii::t('app', 'It\'s a sample command');
    }
}
