<?php

namespace Framework\Entities;

use JsonSerializable;
use ReflectionClass;
use ReflectionMethod;

abstract class AbstractBaseEntity implements JsonSerializable
{
    protected $entityData = [];

    /**
     * Entity constructor. Pass an array or value object
     * to be used to populate the entity data property
     * of this object.
     *
     * @param $data
     */
    final public function __construct($data)
    {
        if (is_object($data))
        {
            $data = get_object_vars($data);
        }

        $this->entityData = $data;
    }

    /**
     * Get the value of a named key from the
     * entityData property of this object. If
     * a corresponding method name is found,
     * return the results of that method instead.
     *
     * @param $name
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        $method = $this->getMethodName($name);

        if (method_exists($this, $method))
        {
            return $this->$method();
        }

        if (array_key_exists($name, $this->entityData))
        {
            return $this->entityData[$name];
        }

        return null;
    }

    public function __set($name, $value)
    {
        $this->entityData[$name] = $value;
    }

    /**
     * Export the entity data as an array
     * with fully processed entity data.
     *
     * @return array
     */
    final public function export()
    {
        return $this->processEntityData();
    }

    /**
     * Serialize the entity data as a json string
     * with fully processed entity data.
     *
     * @return mixed|string
     */
    final public function jsonSerialize()
    {
        return json_encode($this->processEntityData());
    }

    /**
     * Process the entity data properties for export.
     * Here we use a copy of the entity data, instead
     * of modifying it directly. This avoids overwriting
     * the base entity data with the processed data.
     *
     * @return array
     */
    protected function processEntityData()
    {
        $reflection = new ReflectionClass($this);
        $publicMethods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

        $copyOfEntityData = $this->entityData;

        foreach ($publicMethods as $method)
        {
            if (substr($method->name, 0, 2) === 'get')
            {
                $dataKeyName = $this->getDataKeyForMethod($method->name);

                $copyOfEntityData[$dataKeyName] = $this->{$method->name}();
            }
        }

        return $copyOfEntityData;
    }

    /**
     * Transforms a property name into a method
     * name. Swaps snake_case to camelCase
     *
     * @param $name
     */
    private function getMethodName($name)
    {
        return 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
    }

    /**
     * Transforms a method name into a property name.
     *
     * This strips the `get` from the beginning of the,
     * method name then splits into an array on the
     * uppercase letters. From there, it implodes the
     * parts on `_` and finally makes the whole thing
     * lowercase to match the property name.
     *
     * @param string $method
     */
    private function getDataKeyForMethod($method)
    {
        return strtolower(implode('_', preg_split('/(?=[A-Z])/', substr($method, 3), -1, PREG_SPLIT_NO_EMPTY)));
    }
}
