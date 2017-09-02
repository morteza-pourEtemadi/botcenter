<?php

namespace common\components\telegram\commands\botId_2;

use common\models\bot\botId_2\Hadith;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class HadithCommand extends CommandLocal
{
    protected $name = 'hadith';
    protected $description = 'It\'s a command to send daily hadith';
    protected $pattern = '/hadith';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $day = (int) date('z') + 1;
        $year = date('y');
        $prop = (int) ($year) % 4;
        $index = $prop * 365 + $day;
        $hadith = Hadith::findOne(['index' => $index]);

        $message = $hadith->quoter . "\n";
        $message.= $hadith->quote . "\n\n";
        $message.= $hadith->quoter_trans . "\n";
        $message.= $hadith->quote_trans . "\n\n";
        $message.= $hadith->source;

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
