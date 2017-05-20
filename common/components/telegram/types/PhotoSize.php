<?php
/**
 * @link http://www.noghteh.ir/
 * @copyright Copyright (c) 2015 Noghteh
 * @license http://www.noghteh.ir/license/
 */

namespace common\components\telegram\types;

/**
 * PhotoSize
 *
 * @property string $file_id
 * @property int $width
 * @property int $height
 * @property int $file_size
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */

class PhotoSize extends BaseType
{
    /**
     * The Telegram image file mime Type.
     */
    const MIME_TYPE = 'image/jpeg';

    public $file_id;
    public $width;
    public $height;
    public $file_size;

    /**
     * Retrieve the Telegram image file mime Type.
     */
    public function getMimeType()
    {
        return self::MIME_TYPE;
    }
}
