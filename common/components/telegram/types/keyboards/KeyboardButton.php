<?php

namespace common\components\telegram\types\keyboards;

use common\components\telegram\types\BaseType;

/**
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class KeyboardButton extends BaseType
{
    public $text = "";
    public $request_contact = false;
    public $request_location = false;

    public static function setNewKeyButton($text, $contact = false, $location = false)
    {
        return new KeyboardButton([
            'text' => $text,
            'request_contact' => $contact,
            'request_location' => $location
        ]);
    }
}
