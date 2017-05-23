<?php

namespace common\components\telegram\commands\botId_1;

use common\models\bot\botId_1\X;
use Yii;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class VoteLinkCommand extends CommandLocal
{
    protected $name = 'voteLink';
    protected $description = 'command to get a vote link';
    protected $pattern = '/voteLink';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $input = explode(' ', $this->_messageText);
        if (isset($input[1])) {
            $item = X::findOne(['id' => $input[1]]);
            $url = 'https://tlgrm.me/' . $this->bot->username . '?start=' . $item->code;

            $message = Yii::t('app_1', "Please vote for my clip. It`s an exciting competition with great prizes\n\nClick the following link to watch my dubsmash and vote for it\n");
            $message .= $url;

            $this->setPartKeyboard('competition');
            $this->sendMessage($message);
        } else {
            $items = X::findAll(['creator_id' => $this->_chatId]);
            $ids = [];
            foreach ($items as $item) {
                $ids[]['id'] = $item->id;
                $ids[]['caption'] = $item->caption != '' ? $item->caption : Yii::t('app_1', 'No Caption');
            }
            $this->setCache(['wci_ids' => $ids]);
            $this->setPartKeyboard('whichItem');
            $this->sendMessage(Yii::t('app_1', 'which item you need link for?'));
        }

        return true;
    }
}
