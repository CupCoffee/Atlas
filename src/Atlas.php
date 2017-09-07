<?php

namespace Lorey\Atlas;

use InvalidArgumentException;
use ReflectionClass;

class Atlas
{
    const PROPERTY_TYPE_PATTERN = "/@var\\s*([^\\s]+)/";

    /**
     * @var Property[]
     */
    private $properties = [];

    /**
     * @var ReflectionClass
     */
    private $reflectedType;

    public function __construct($type)
    {
        if (!is_object($type) && !class_exists($type)) {
            throw new InvalidArgumentException("Type $type does not exist");
        }

        $this->reflectedType = new ReflectionClass($type);

        $this->mapProperties($this->reflectedType);
    }

    public static function map($type)
    {
        return new Atlas($type);
    }

    private function buildProperty($name, $type): Property
    {
        $isArray = false;
        $isNullable = false;

        if (strpos($type, '?') !== false) {
            $type = str_replace("?", "", $type);
            $isNullable = true;
        }

        if (strpos($type, "[]") !== false) {
            $type = str_replace("[]", "", $type);
            $isArray = true;
        }

        return new Property($name, $type, $isNullable, $isArray);
    }

    private function mapProperties(ReflectionClass $reflectedType)
    {
        foreach($reflectedType->getProperties() as $property) {
            $matches = [];
            preg_match(static::PROPERTY_TYPE_PATTERN, $property->getDocComment(), $matches);

            if ($matches) {
                $this->properties[$property->getName()] = $this->buildProperty($property->getName(), $matches[1]);
            }
        }
    }

    /**
     * @return Property[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function hasProperty($name)
    {
        return isset($this->properties[$name]);
    }

    public function getProperty($name)
    {
        if (!$this->hasProperty($name)) {
            $type = $this->reflectedType->name;
            throw new AtlasException("Property $$name not found in class $type");
        }

        return $this->properties[$name];
    }
}
