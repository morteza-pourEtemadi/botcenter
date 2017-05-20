<?php
/**
 * @link http://www.noghteh.ir/
 * @copyright Copyright (c) 2015 Noghteh
 * @license http://www.noghteh.ir/license/
 */

namespace common\components\telegram\types;

/**
 * User
 *
 * @property int $id
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class User extends BaseType
{
    public $id;
    public $username;
    public $first_name;
    public $last_name;
}