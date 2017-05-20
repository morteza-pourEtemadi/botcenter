<?php
/**
 * @link http://www.noghteh.ir/
 * @copyright Copyright (c) 2015 Noghteh
 * @license http://www.noghteh.ir/license/
 */

namespace common\components\telegram\types;

/**
 * Document
 *
 * @property string $file_id
 * @property PhotoSize $thumb;
 * @property string $mime_type
 * @property string $file_name
 * @property int $file_size
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class Document extends BaseType
{
    public $file_id;
    public $thumb;
    public $mime_type;
    public $file_size;
    public $file_name;

    /**
     * @inheritdoc
     */
    public function objectMap()
    {
        return [
            'thumb' => PhotoSize::className(),
        ];
    }
}
