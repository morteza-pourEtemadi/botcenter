<?php

namespace common\components\telegram\commands\botId_16;

use Yii;
use common\models\botId_16\Placement;
use common\traits\TelegramCommandTrait;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class NewPlacementCommand extends CommandLocal
{
    use TelegramCommandTrait;

    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/newPlacement';
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
            $placement = new Placement([
                'category' => 0,
                'subcategory' => $text[2],
                'create_time' => time(),
                'creator_id' => $this->_chatId,
                'status' => Placement::STATUS_WRITING,
            ]);
            $placement->save(false);
            $this->setReply(['id' => $placement->id, 'text' => 1]);
            $this->page = $placement->id;
            $this->setPartKeyboard('cancelPlacement');
            $this->sendMessage(Yii::t('app_16', 'now enter your placement text'));
        } else {
            $placement = Placement::findOne(['id' => $this->getReply()['id']]);
            if (isset($this->getReply()['text'])) {
                if ($this->checkInputMessage($this->_messageText) == false) {
                    return false;
                }
                $placement->text = $this->_messageText;
                $placement->status = Placement::STATUS_PENDING;
                $placement->save(false);

                $this->api->sendMessage(88123885, Yii::t('app_16', 'new placement to confirm'));
                $this->api->sendMessage(104098344, Yii::t('app_16', 'new placement to confirm'));

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
