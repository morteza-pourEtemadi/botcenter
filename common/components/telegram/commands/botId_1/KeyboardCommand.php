<?php

namespace common\components\telegram\commands\botId_1;

use Yii;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class KeyboardCommand extends CommandLocal
{
    protected $name = 'keyboard';
    protected $description = 'This command is used to navigate through pagination keyboard';
    protected $pattern = '/keyboard';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $input = $this->getInput();
        if (isset($input[2]) === false) {
            return false;
        }

        $this->setPartKeyboard($input[1], $input[2]);
        $this->api->editMessageReplyMarkup($this->_chatId, $this->update->callback_query->message->message_id, $this->getKeyboard());
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
