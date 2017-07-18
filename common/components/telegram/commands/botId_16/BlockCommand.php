<?php

namespace common\components\telegram\commands\botId_16;

use common\components\RedisActiveRecord;
use common\models\botId_16\Leads;
use common\models\botId_16\News;
use common\models\botId_16\Placement;
use Yii;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class BlockCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/block';
    protected $public = false;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $text = explode(' ', $this->_messageText);
        if (!isset($text[2])) {
            return false;
        }

        $page = explode(':', $text[2]);

        if ($text[1] == 'lead') {
            $class = Leads::className();
        } elseif ($text[1] == 'placement') {
            $class = Placement::className();
        } else {
            $class = News::className();
        }

        /* @var Leads|Placement|News $class */
        if ($text[1] == 'news') {
            $objects = $class::find()->all();
            $page3 = $text[2];
        } else {
            $page1 = $page[0];
            $page2 = $page[1];
            $page3 = $page[2];
            $objects = $class::findAll(['category' => $page1, 'subcategory' => $page2, 'status' => 1]);
        }
        usort($objects, function ($a, $b) {
            /* @var News $a */
            /* @var News $b */
            if ($a->create_time > $b->create_time) {
                return -1;
            }
            return $a->create_time < $b->create_time ? 1 : 0;
        });
        $object = $objects[$page3];

        if ($text[1] == 'news') {
            $object->delete();
        } else {
            $object->status = $class::STATUS_DENIED;
            $object->save();
        }

        $this->setPartKeyboard('adminsRule');
        $this->sendMessage(Yii::t('app_16', 'blocked'));
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
