<?php
/**
 * @link http://www.noghteh.ir/
 * @copyright Copyright (c) 2015 Noghteh
 * @license http://www.noghteh.ir/license/
 */

namespace common\components\telegram\types;

/**
 * Class Chat
 *
 * @property int $id
 * @property string $name
 * @property string $first_name
 * @property string $last_name
 * @property string $title
 * @property string $username
 * @property string $type
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class Chat extends BaseType
{
    const TYPE_PRIVATE = 'private';
    const TYPE_GROUP = 'group';
    const TYPE_SUPER_GROUP = 'supergroup';
    const TYPE_CHANNEL = 'channel';

    public $id;
    public $type;
    public $title;
    public $username;
    public $first_name;
    public $last_name;

    public function isPrivate()
    {
        return $this->type === self::TYPE_PRIVATE;
    }

    public function isGroup()
    {
        return $this->type === self::TYPE_GROUP;
    }

    public function isSuperGroup()
    {
        return $this->type === self::TYPE_SUPER_GROUP;
    }

    public function isChannel()
    {
        return $this->type === self::TYPE_CHANNEL;
    }

    /**
     * Returns chat name
     * @return string
     */
    public function getName()
    {
        if ($this->isPrivate() === false) {
            return $this->title;
        }

        $name = $this->first_name;
        $name .= $this->last_name ? ' ' . $this->last_name : '';
        return $name;
    }
}