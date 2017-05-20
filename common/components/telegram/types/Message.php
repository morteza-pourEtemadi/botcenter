<?php
/**
 * @link http://www.noghteh.ir/
 * @copyright Copyright (c) 2015 Noghteh
 * @license http://www.noghteh.ir/license/
 */

namespace common\components\telegram\types;

/**
 * Class Message
 *
 * @property int $message_id
 * @property string $text
 * @property string $caption
 * @property int $date
 * @property int $forward_date
 * @property int $forward_from_message_id
 * @property Chat $forward_from
 * @property Chat $forward_from_chat
 * @property User $from
 * @property Chat $chat
 * @property User $new_chat_member
 * @property User $left_chat_member
 * @property Message $reply_to_message
 * @property string $content
 * @property Audio $audio
 * @property Video $video
 * @property Voice $voice
 * @property PhotoSize[] $photo
 * @property Document $document
 * @property Location $location
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class Message extends BaseType
{
    public $message_id;
    public $text;
    public $caption;
    public $date;
    public $forward_date;
    public $from;
    public $chat;
    public $new_chat_member;
    public $left_chat_member;
    public $reply_to_message;
    public $forward_from;
    public $forward_from_chat;
    public $forward_from_message_id;
    public $migrate_to_chat_id;
    public $audio;
    public $video;
    public $voice;
    public $photo;
    public $document;
    public $location;
    public $entities;

    /**
     * @inheritdoc
     */
    public function objectMap()
    {
        return [
            'from' => User::className(),
            'chat' => Chat::className(),
            'forward_from' => Chat::className(),
            'forward_from_chat' => Chat::className(),
            'new_chat_member' => User::className(),
            'left_chat_member' => User::className(),
            'reply_to_message' => self::className(),
            'photo' => PhotoSize::className(),
            'audio' => Audio::className(),
            'video' => Video::className(),
            'voice' => Voice::className(),
            'document' => Document::className(),
            'location' => Location::className(),
            'entities' => Entities::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function arrayObjects()
    {
        return [
            'photo',
            'entities',
        ];
    }

    /**
     * Return text of any types
     * @return string
     */
    public function getContent()
    {
        if ($this->text) {
            return $this->text;
        }

        if ($this->caption) {
            return $this->caption;
        }

        if ($this->document) {
            return $this->document->file_name;
        }

        return null;
    }

    /**
     * Retrieve the Message's file identifier based on its type.
     * @return null|string
     */
    public function getFileId()
    {
        if ($this->isVideo()) {
            return $this->video->file_id;
        } elseif ($this->isPhoto()) {
            return end($this->photo)->file_id;
        } elseif ($this->isAudio()) {
            return $this->audio->file_id;
        } elseif ($this->isDocument()) {
            return $this->document->file_id;
        }

        return null;
    }

    /**
     * Retrieve the Message's file mime type based on its type.
     * @return null|string
     */
    public function getMimeType()
    {
        if ($this->isVideo()) {
            return $this->video->getMimeType();
        } elseif ($this->isPhoto()) {
            return $this->photo[0]->getMimeType();
        } elseif ($this->isAudio()) {
            return $this->audio->getMimeType();
        } elseif ($this->isDocument()) {
            return $this->document->mime_type;
        }

        return null;
    }

    /**
     * Determine if the Message's type is photo.
     * @return boolean
     */
    public function isPhoto()
    {
        return $this->photo !== null;
    }

    /**
     * Determine if the Message's type is document.
     * @return boolean
     */
    public function isDocument()
    {
        return $this->document !== null;
    }

    /**
     * Determine if the Message's type is video.
     * @return boolean
     */
    public function isVideo()
    {
        return $this->video !== null;
    }

    /**
     * Determine if the Message's type is audio.
     * @return boolean
     */
    public function isAudio()
    {
        return $this->audio !== null;
    }

    /**
     * Is left chat message
     * @return bool
     */
    public function isLeftChat()
    {
        return $this->left_chat_member !== null;
    }

    /**
     * Is new chat message
     * @return bool
     */
    public function isNewChat()
    {
        return $this->new_chat_member !== null;
    }
}
