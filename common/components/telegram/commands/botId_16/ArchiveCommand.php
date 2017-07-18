<?php

namespace common\components\telegram\commands\botId_16;

use common\models\botId_16\News;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class ArchiveCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/archive';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        if (1 < 2) {
            $this->setPartKeyboard('main');
            $this->sendMessage(Yii::t('app_16', 'use new buttons'));
            return true;
        }
        $text = explode(' ', $this->_messageText);

        $news = News::find()->all();
        if (count($news) == 0) {
            $this->setPartKeyboard('main');
            $this->sendMessage(Yii::t('app_16', 'there is no news'));

            return true;
        }

        usort($news, function ($a, $b) {
            /* @var News $a */
            /* @var News $b */
            if ($a->create_time > $b->create_time) {
                return -1;
            }
            return $a->create_time < $b->create_time ? 1 : 0;
        });

        if (isset($text[1])) {
            $this->page = $text[1];
        } else {
            $this->page = 0;
        }

        $thisNews = $news[$this->page];

        $this->setPartKeyboard('newsPage');
        $this->sendProper($thisNews);

        return true;
    }

    public function sendProper($news)
    {
        /* @var News $news */
        $attachments = explode(':', $news->attachments);
        if ($this->getType($news) == 'forward') {
            $attachments = Json::decode($news->attachments);
            $this->api->forwardMessage($this->_chatId, $attachments['chatId'], $attachments['messageId']);
            $this->api->sendMessage($this->_chatId, Yii::t('app_16', 'news archive'), null, $this->getKeyboard(), 'HTML');
        } elseif ($this->getType($news) == 'file') {
            $this->api->sendMessage($this->_chatId, $this->getFilePath($attachments[0]) . $news->text, null, $this->getKeyboard(), 'HTML');
        } else {
            $this->sendMessage($news->text);
        }
        return true;
    }

    public function getType($news)
    {
        /* @var News $news */
        $attachments = explode(':', $news->attachments);
        if (isset($attachments[1])) {
            return 'forward';
        } elseif (isset($attachments[0])) {
            return 'file';
        } else {
            return 'text';
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
