<?php

namespace common\components\telegram\types\keyboards;

use common\components\telegram\types\BaseType;

/**
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class InlineKeyboardButton extends BaseType
{
    public $text = "";
    public $url = "";
    public $callback_data = "";
    public $switch_inline_query = "";

    public static function setNewKeyButton($text, $data, $url = "")
    {
        return new InlineKeyboardButton([
            'text' => $text,
            'callback_data' => $data,
            'url' => $url,
        ]);
    }
}
