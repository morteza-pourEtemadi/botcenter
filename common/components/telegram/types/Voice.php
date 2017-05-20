<?php
/**
 * @link http://www.noghteh.ir/
 * @copyright Copyright (c) 2015 Noghteh
 * @license http://www.noghteh.ir/license/
 */

namespace common\components\telegram\types;

/**
 * Voice
 *
 * @property string $file_id
 * @property int $duration
 * @property string $mime_type
 * @property int $file_size
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class Voice extends BaseType
{
    public $file_id;
    public $duration;
    public $mime_type;
    public $file_size;
}
