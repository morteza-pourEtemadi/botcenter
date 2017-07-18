<?php
/**
 * @link http://www.noghteh.ir/
 * @copyright Copyright (c) 2015 Noghteh
 * @license http://www.noghteh.ir/license/
 */

namespace common\traits;

use yii;
use yii\base\ErrorException;
use common\component\telegram\types\Message;
use common\component\localStorage\TelegramBucket;

/**
 * A set of common functionality among Commands.
 *
 * @property \common\components\TelegramBot $api
 *
 * @method mixed sendMessage($text, $replyMessageId = null)
 *
 * @author Mohammad Davaee <mdavaee@gmail.com>
 */
trait TelegramCommandTrait
{
    /**
     * Download and save Telegram file content to local dick and Media service.
     *
     * @param \stdClass $telegramFileObject The Telegram File Object.
     *
     * @return TelegramBucket
     * @throws ErrorException
     */
    public function saveTelegramFile($telegramFileObject)
    {
        $bucket = new TelegramBucket(compact('telegramFileObject'));

        // Download file content via Telegram file link
        if ($fileContent = $this->api->downloadFile($telegramFileObject->file_path)) {
            // Store downloaded content to the disk
            if ($file = $bucket->save($fileContent)) {
                return $file;
            }

            throw new ErrorException('Cannot save file content to the disk');
        }

        throw new ErrorException('Cannot download Telegram file content');
    }

    /**
     * Get The Telegram Message's attached file if there is any one.
     * Throw an Exception If the attached file isn't in supported file's media types rang.
     *
     * @param Message $message
     *
     * @return null|TelegramBucket
     * @throws ErrorException
     */
    public function getAttachedFile(Message $message)
    {
        $mediaType = $message->getMimeType();

        // Check if replied message contains of supported file's media types or not.
        if ($mediaType !== null) {
            $response = $this->api->getFile($message->getFileId());

            if ($response->ok === false) {
                Yii::error(['message' => 'Telegram getFile() Error', 'response' => $response], __METHOD__);
                return null;
            }

            $telegramFile = $response->result;
            return $this->saveTelegramFile($telegramFile);
        }

        // Check if replied message is just a text message. So we have not any attachments.
        if ($message->text) {
            return null;
        }

        /*
         * Handel not supported media_type by sent a message to user and throw an exception.
         */
        $error = Yii::t('app', 'Currently we don\'t support your message media content type.');
        $this->sendMessage($error);

        throw new ErrorException($error);
    }
}
