<?php
/**
 * @link http://www.noghteh.ir/
 * @copyright Copyright (c) 2015 Noghteh
 * @license http://www.noghteh.ir/license/
 */

namespace common\components\telegram\types;

/**
 * Audio
 *
 * @property string $file_id
 * @property int $duration
 * @property string $performer
 * @property string $title
 * @property string $mime_type
 * @property int $file_size
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class Audio extends BaseType
{
    /**
     * The Telegram audio file mime Type.
     */
    const MIME_TYPE = 'audio/mpeg';

    public $file_id;
    public $duration;
    public $performer;
    public $title;
    public $mime_type;
    public $file_size;

    /**
     * Retrieve the Telegram audio file mime Type.
     */
    public function getMimeType()
    {
        return self::MIME_TYPE;
    }
}
