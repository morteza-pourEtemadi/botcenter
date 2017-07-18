<?php

namespace common\components\telegram\commands\botId_16;

use Yii;
use common\models\botId_16\Leads;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class SubmitLeadsCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/submitLeads';
    protected $public = false;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $text = explode(' ', $this->_messageText);
        $remainPics = count(Leads::findAll(['status' => Leads::STATUS_PENDING]));
        $remainPics += count(Leads::findAll(['status' => Leads::STATUS_SUBMITTING]));
        if (isset($text[2])) {
            $pic = Leads::findOne(['id' => $text[2]]);
            $keys = $this->main();
            $buttons = [];
            foreach ($keys as $key => $value) {
                $buttons[] = [$value];
            }
            $keyboard = [
                'inline_keyboard' => $buttons
            ];
            if ($text[1] === '1') {
                $pic->status = Leads::STATUS_CONFIRMED;
                $this->api->sendMessage($pic->creator_id, Yii::t('app_16', 'your lead confirmed'), null, $keyboard);
                $this->api->sendMessage('@bazarchapiran', $pic->text . "\n\n\n" . Yii::t('app_16', 'signature text'));
            } else {
                $pic->status = Leads::STATUS_DENIED;
                $this->api->sendMessage($pic->creator_id, Yii::t('app_16', 'your lead denied'), null, $keyboard);
            }
            $pic->save();
            $pic = Leads::findOne(['status' => Leads::STATUS_PENDING]);
            if ($pic) {
                $pic->status = Leads::STATUS_SUBMITTING;
                $pic->save();

                $this->killKeyboard();
                $this->sendMessage(Yii::t('app_16', 'count of remained leads: {count}', ['count' => $remainPics - 1]));

                $message = $pic->text;

                $this->killKeyboard();
                $this->sendMessage($message);

                $this->page = $pic->id;
                $this->setPartKeyboard('submitLead');
                $this->sendMessage(Yii::t('app_16', 'submit or deny?'));
            } else {
                $pic = Leads::findOne(['status' => Leads::STATUS_SUBMITTING]);
                if ($pic) {
                    $message = $pic->text;

                    $this->killKeyboard();
                    $this->sendMessage($message);
                    
                    $this->page = $pic->id;
                    $this->setPartKeyboard('submitLead');
                    $this->sendMessage(Yii::t('app_16', 'submit or deny?'));
                } else {
                    $this->setPartKeyboard('adminsRule');
                    $this->sendMessage(Yii::t('app_16', 'All done'));
                }
            }
        } else {
            $pic = Leads::findOne(['status' => Leads::STATUS_PENDING]);
            if ($pic) {
                $pic->status = Leads::STATUS_SUBMITTING;
                $pic->save();

                $this->killKeyboard();
                $this->sendMessage(Yii::t('app_16', 'count of remained pictures: {count}', ['count' => $remainPics]));

                $message = $pic->text;
                $this->sendMessage($message);

                $this->page = $pic->id;
                $this->setPartKeyboard('submitLead');
                $this->sendMessage(Yii::t('app_16', 'submit or deny?'));
            } else {
                $pic = Leads::findOne(['status' => Leads::STATUS_SUBMITTING]);
                if ($pic) {
                    $message = $pic->text;

                    $this->killKeyboard();
                    $this->sendMessage($message);

                    $this->page = $pic->id;
                    $this->setPartKeyboard('submitLead');
                    $this->sendMessage(Yii::t('app_16', 'submit or deny?'));
                } else {
                    $this->setPartKeyboard('adminsRule');
                    $this->sendMessage(Yii::t('app_16', 'All done'));
                }
            }
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
