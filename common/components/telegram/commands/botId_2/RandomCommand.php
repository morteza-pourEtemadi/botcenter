<?php

namespace common\components\telegram\commands\botId_2;

use Yii;
use common\models\bot\botId_2\Quran;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class RandomCommand extends CommandLocal
{
    protected $name = 'random';
    protected $description = 'It\'s a command to get a random aya';
    protected $pattern = '/random';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $rnd = mt_rand(1, 6236);
        $aya = Quran::findOne(['index' => $rnd]);

        $message = $aya->text . "\n";
        $message.= $aya->translation . "\n\n";
        $message.= Yii::t('app_2', 'holy sura') . ' ' . $aya->sura . ' ' . Yii::t('app_2', 'holy aya') . ' ' . $aya->aya;

        $this->setMainKeyboard();
        $this->sendMessage($message);

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
