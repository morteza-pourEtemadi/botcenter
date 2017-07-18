<?php

namespace common\components\telegram\commands\botId_16;

use Yii;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class KeyboardCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/keyboard';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $input = explode(' ', $this->_messageText);
        if (isset($input[3]) === false) {
            return false;
        }

        $this->setPartKeyboard($input[1], [], $input[2]);
        $this->api->editMessageReplyMarkup($this->_chatId, $this->update->callback_query->message->message_id, $this->getKeyboard());
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
