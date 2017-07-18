<?php

namespace common\components\telegram\commands\botId_16;

use common\models\botId_16\Shows;
use Yii;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class DeleteShowsCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/deleteShows';
    protected $public = false;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $text = explode(' ', $this->_messageText);
        if (isset($text[1])) {
            $recipe = Shows::findOne(['id' => $text[1]]);
            $recipe->delete();
            $this->setPartKeyboard('adminsRule');
            $this->sendMessage(Yii::t('app_16', 'show deleted successfully'));
            return true;
        } else {
            $this->setPartKeyboard('deleteRecipe1');
            $this->sendMessage(Yii::t('app_16', 'select the show you want to delete'));
            return true;
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
