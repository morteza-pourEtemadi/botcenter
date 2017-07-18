<?php

namespace common\components\telegram\commands\botId_16;

use common\models\botId_16\Placement;
use Yii;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class PlacementsCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/placements';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $text = explode(' ', $this->_messageText);
        if (isset($text[2])) {
            $placements = Placement::findAll(['category' => 0, 'subcategory' => $text[2], 'status' => Placement::STATUS_CONFIRMED]);
            if (count($placements) == 0) {
                $this->page = $text[2];
                $this->setPartKeyboard('firstPlacement');
                $this->sendMessage(Yii::t('app_16', 'be the first person to create a placement'));

                return true;
            }

            usort($placements, function ($a, $b) {
                /* @var Placement $a */
                /* @var Placement $b */
                if ($a->create_time > $b->create_time) {
                    return -1;
                }
                return $a->create_time < $b->create_time ? 1 : 0;
            });

            $message = '';
            for ($i = 0; $i < 3; $i++) {
                if ((isset($text[3]) && $text[3] + $i >= count($placements)) || $i >= count($placements)) {
                    break;
                }
                $lead = $placements[(isset($text[3]) ? $text[3] + $i : $i)];
                $message .= $lead->text . "\n\n--------------------------------------------------\n\n";
            }

            $this->page = $text[1] . ':' . $text[2] . ':' . (isset($text[3]) ? $text[3] : '0');
            $this->setPartKeyboard('placementsPage');
            $this->sendMessage($message);
        } elseif (isset($text[1])) {
            $this->page = $text[1];
            $this->setPartKeyboard('subPlacements');
            $this->sendMessage(Yii::t('app_16', 'choose which placements'));
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
