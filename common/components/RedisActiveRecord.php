<?php

namespace common\components;

use yii\helpers\Inflector;
use yii\redis\ActiveRecord;

/**
 * Class RedisActiveRecord
 * @package common\component
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class RedisActiveRecord extends ActiveRecord
{
    public static function keyPrefix()
    {
        $array = explode('\\', get_called_class());
        if (count($array) === 1) {
            $array = explode('/', get_called_class());
        }
        $prefix = $array[count($array) - 2] . end($array);
        $prefix = Inflector::camel2id($prefix, '_');
        return $prefix;
    }
}
