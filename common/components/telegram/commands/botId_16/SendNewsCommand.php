<?php

namespace common\components\telegram\commands\botId_16;

use Yii;
use yii\helpers\Json;
use common\models\botId_16\News;
use common\models\botId_16\User;
use PhpAmqpLib\Message\AMQPMessage;
use common\traits\TelegramCommandTrait;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class SendNewsCommand extends CommandLocal
{
    use TelegramCommandTrait;

    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/sendNews';
    protected $public = false;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $text = explode(' ', $this->_messageText);

        if (isset($text[1]) && $text[1] == 'done') {
            $news = News::findOne(['id' => $this->getReply()['id']]);
            $news->attachments = '';
            $news->save();
            $this->killReply();

            $this->page = $news->id;
            $this->setPartKeyboard('sendNews');
            $this->sendMessage($news->text);

            return true;
        } elseif (isset($text[1]) && $text[1] == 'all') {
            $news = News::findOne(['id' => $text[2]]);

            $json = explode(':', $news->attachments);
            if ($news->attachments == '') {
                $message = 'reply*:*text*:*' . $news->text;
            } elseif (isset($json[1])) {
                $json = Json::decode($news->attachments);
                $message = 'forward*:*' . $json['chatId'] . '*:*' . $json['messageId'];
            } else {
                $message = 'reply*:*file*:*' . $news->attachments . '*:*' . $news->text;
            }

            $this->killReply();
            $this->sendToAll($message);
            return true;
        }

        if ($this->isReply) {
            $news = News::findOne(['id' => $this->getReply()['id']]);

            if (isset($this->getReply()['message'])) {
                if (isset($this->update->message->forward_date)) {
                    if (isset($this->update->message->forward_from_chat)) {
                        $chatId = '@' . $this->update->message->forward_from_chat->username;
                        $messageId = $this->update->message->forward_from_message_id;
                    } else {
                        $chatId = $this->update->message->chat->id;
                        $messageId = $this->update->message->message_id;
                    }
                    $news->text = '';
                    $news->attachments = json_encode(['chatId' => $chatId, 'messageId' => $messageId]);
                    $news->save();

                    $this->killReply();
                    $this->api->forwardMessage($this->_chatId, $chatId, $messageId);

                    $this->page = $news->id;
                    $this->setPartKeyboard('sendNews');
                    $this->sendMessage(Yii::t('app_16', 'send?'));

                    return true;
                }

                $news->text = $this->_messageText;
                $news->save();

                $this->killReply();
                $this->setReply(['attachment' => 1, 'id' => $news->id]);

                $this->page = $news->id;
                $this->setPartKeyboard('addNewsAttachments');
                $this->sendMessage(Yii::t('app_16', 'now send an attachment'));

                return true;
            } elseif (isset($this->getReply()['attachment'])) {
                $this->addFileAttachment($news);
                if (isset($news->attachments) && $news->attachments !== '') {
                    $message = $this->getFilePath($news->attachments) . "\n";
                    $message .= $news->text;

                    $this->killReply();
                    $this->page = $news->id;
                    $this->setPartKeyboard('sendNews');
                    $this->sendMessage($message);

                    return true;
                }
            }
        } else {
            $allNews = News::find()->all();
            $index = count($allNews);

            $news = new News([
                'index' => $index,
                'title' => '',
                'create_time' => time(),
            ]);
            $news->save(false);

            $this->setReply(['message' => 1, 'id' => $news->id]);
            $this->killKeyboard();
            $this->sendMessage(Yii::t('app_16', 'now enter the news text or forward a message'));
        }

        return true;
    }


    public function isRepliedMessage()
    {
        return isset($this->update->message->reply_to_message->message_id);
    }

    public function sendToAll($message)
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('sendNews', false, false, false, false);

        $users = User::find()->all();

        foreach ($users as $user) {
            $fullMessage = $user->user_id . '*:*' . $message;
            $msg = new AMQPMessage($fullMessage);
            $channel->basic_publish($msg, '', 'sendNews');
        }

        $channel->close();
        $connection->close();
    }

    /**
     * @param News $news
     */
    public function addFileAttachment($news)
    {
        if (isset($this->update->message->text) === false) {
            $attachment = $this->update->message->getFileId();
            $file = $this->getAttachedFile($this->update->message);
            rename(Yii::getAlias('@frontend/uploads/') . $file->path, Yii::getAlias('@frontend/web/attachments/') . $file->path);
            $news->attachments = $attachment;
            $news->save();
        } else {
            $this->sendMessage(Yii::t('app_16', 'just attachments'));
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
