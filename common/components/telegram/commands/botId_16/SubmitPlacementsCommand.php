<?php

namespace common\components\telegram\commands\botId_16;

use Yii;
use common\models\botId_16\Placement;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class SubmitPlacementsCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/submitPlacements';
    protected $public = false;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $text = explode(' ', $this->_messageText);
        $remainPics = count(Placement::findAll(['status' => Placement::STATUS_PENDING]));
        $remainPics += count(Placement::findAll(['status' => Placement::STATUS_SUBMITTING]));
        if (isset($text[2])) {
            $pic = Placement::findOne(['id' => $text[2]]);
            $keys = $this->main();
            $buttons = [];
            foreach ($keys as $key => $value) {
                $buttons[] = [$value];
            }
            $keyboard = [
                'inline_keyboard' => $buttons
            ];
            if ($text[1] === '1') {
                $pic->status = Placement::STATUS_CONFIRMED;
                $this->api->sendMessage($pic->creator_id, Yii::t('app_16', 'your Placement confirmed'), null, $keyboard);
                $this->api->sendMessage('@karyabichap', $pic->text . "\n\n\n" . Yii::t('app_16', 'signature text'));
            } else {
                $pic->status = Placement::STATUS_DENIED;
                $this->api->sendMessage($pic->creator_id, Yii::t('app_16', 'your placement denied'), null, $keyboard);
            }
            $pic->save();
            $pic = Placement::findOne(['status' => Placement::STATUS_PENDING]);
            if ($pic) {
                $pic->status = Placement::STATUS_SUBMITTING;
                $pic->save();

                $this->killKeyboard();
                $this->sendMessage(Yii::t('app_16', 'count of remained Placement: {count}', ['count' => $remainPics - 1]));

                $message = $pic->text;

                $this->killKeyboard();
                $this->sendMessage($message);

                $this->page = $pic->id;
                $this->setPartKeyboard('submitPlacement');
                $this->sendMessage(Yii::t('app_16', 'submit or deny?'));
            } else {
                $pic = Placement::findOne(['status' => Placement::STATUS_SUBMITTING]);
                if ($pic) {
                    $message = $pic->text;

                    $this->killKeyboard();
                    $this->sendMessage($message);
                    
                    $this->page = $pic->id;
                    $this->setPartKeyboard('submitPlacement');
                    $this->sendMessage(Yii::t('app_16', 'submit or deny?'));
                } else {
                    $this->setPartKeyboard('adminsRule');
                    $this->sendMessage(Yii::t('app_16', 'All done'));
                }
            }
        } else {
            $pic = Placement::findOne(['status' => Placement::STATUS_PENDING]);
            if ($pic) {
                $pic->status = Placement::STATUS_SUBMITTING;
                $pic->save();

                $this->killKeyboard();
                $this->sendMessage(Yii::t('app_16', 'count of remained pictures: {count}', ['count' => $remainPics]));

                $message = $pic->text;
                $this->sendMessage($message);

                $this->page = $pic->id;
                $this->setPartKeyboard('submitPlacement');
                $this->sendMessage(Yii::t('app_16', 'submit or deny?'));
            } else {
                $pic = Placement::findOne(['status' => Placement::STATUS_SUBMITTING]);
                if ($pic) {
                    $message = $pic->text;

                    $this->killKeyboard();
                    $this->sendMessage($message);

                    $this->page = $pic->id;
                    $this->setPartKeyboard('submitPlacement');
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
