<?php

namespace common\components\telegram\commands\botId_16;

use common\models\botId_16\Leads;
use Yii;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class LeadsCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/leads';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $text = explode(' ', $this->_messageText);
        if (isset($text[2])) {
            $leads = Leads::findAll(['category' => $text[1], 'subcategory' => $text[2], 'status' => Leads::STATUS_CONFIRMED]);
            if (count($leads) == 0) {
                $this->page = $text[1] . ':' . $text[2];
                $this->setPartKeyboard('firstLead');
                $this->sendMessage(Yii::t('app_16', 'be the first person to create a lead'));

                return true;
            }

            usort($leads, function ($a, $b) {
                /* @var Leads $a */
                /* @var Leads $b */
                if ($a->create_time > $b->create_time) {
                    return -1;
                }
                return $a->create_time < $b->create_time ? 1 : 0;
            });

            $message = '';
            for ($i = 0; $i < 3; $i++) {
                if ((isset($text[3]) && $text[3] + $i >= count($leads)) || $i >= count($leads)) {
                    break;
                }
                $lead = $leads[(isset($text[3]) ? $text[3] + $i : $i)];
                $message .= $lead->text . "\n\n--------------------------------------------------\n\n";
            }

            $this->page = $text[1] . ':' . $text[2] . ':' . (isset($text[3]) ? $text[3] : '0');
            $this->setPartKeyboard('leadsPage');
            $this->sendMessage($message);
        } elseif (isset($text[1])) {
            $this->page = $text[1];
            $this->setPartKeyboard('subLeads');
            $this->sendMessage(Yii::t('app_16', 'choose which sub leads'));
        } else {
            $this->setPartKeyboard('leads');
            $this->sendMessage(Yii::t('app_16', 'choose which leads'));
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
