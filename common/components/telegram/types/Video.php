<?php
/**
 * @link http://www.noghteh.ir/
 * @copyright Copyright (c) 2015 Noghteh
 * @license http://www.noghteh.ir/license/
 */

namespace common\components\telegram\types;

/**
 * Video
 *
 * @property string $file_id
 * @property int $duration
 * @property int $width;
 * @property int $height;
 * @property PhotoSize $thumb;
 * @property string $mime_type
 * @property int $file_size
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */

class Video extends BaseType
{
    /**
     * The Telegram video file mime Type.
     */
    const MIME_TYPE = 'video/mp4';

    public $file_id;
    public $duration;
    public $width;
    public $height;
    public $thumb;
    public $mime_type;
    public $file_size;

    /**
     * @inheritdoc
     */
    public function objectMap()
    {
        return [
            'thumb' => PhotoSize::className(),
        ];
    }

    /**
     * Retrieve the Telegram video file mime Type.
     */
    public function getMimeType()
    {
        return self::MIME_TYPE;
    }
}
