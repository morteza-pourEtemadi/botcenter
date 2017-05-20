<?php

namespace common\components\telegram\commands\botId_1;

use common\models\bot\botId_1\X;
use Yii;

/**
 * ShowItem Command to show items and collect votes
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class ShowItemCommand extends CommandLocal
{
    protected $name = 'showItem';
    protected $description = 'Command to show items to users and collect votes';
    protected $pattern = '/showItem';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $input = explode(' ', $this->_messageText);
        if (isset($input[1]) == false) {
            return false;
        }

        $item = X::findOne(['code' => $input[1]]);
        if ($item) {
            $this->setCache(['code' => $item->code]);
            $this->setPartKeyboard('voteItem');
            $this->sendFile($item->file_id, Yii::t('app_1', 'Vote for this please!'));
        } else {
            $this->setMainKeyboard();
            $this->sendMessage(Yii::t('app_1', 'Something went wrong. There is no file with this link!'));
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
