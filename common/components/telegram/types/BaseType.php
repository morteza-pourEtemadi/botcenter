<?php
/**
 * @link http://www.noghteh.ir/
 * @copyright Copyright (c) 2015 Noghteh
 * @license http://www.noghteh.ir/license/
 */

namespace common\components\telegram\types;

use yii\base\Component;

/**
 * Class BaseType
 *
 * @property \common\models\Bot $bot
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class BaseType extends Component
{
    /**
     * @inheritDoc
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->setterObjectMap();
    }

    /**
     * @inheritDoc
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            // set property
            $this->$setter($value);

            return null;
        }
    }

    /**
     * Set properties as a object with class declared in getObjectMap() method
     */
    public function setterObjectMap()
    {
        if (method_exists($this, 'objectMap') && is_array($objectMap = $this->objectMap())) {
            foreach ($objectMap as $property => $class) {
                if ($this->$property !== null) {
                    $data = isset($this->$property) ? $this->$property : [];
                    if (in_array($property, $this->arrayObjects(), true) && is_array($data)) {
                        $arrayObject = [];
                        foreach ($data as $child) {
                            $arrayObject[] = new $class($child);
                        }
                        $this->$property = $arrayObject;
                    } else {
                        $this->$property = new $class($data);
                    }
                }
            }
        }
    }

    /**
     * A map of objective properties in an array
     * populate with property as key and class name as value
     *
     * Example:
     * ```
     * [
     *     'message' => Message::className()
     * ]
     * ```
     */
    public function objectMap()
    {
        return [];
    }

    /**
     * An array of properties name that contains an array of object type
     * For example photo contains an array of PhotoSize objects in Message object
     * @return array
     */
    public function arrayObjects()
    {
        return [];
    }
}