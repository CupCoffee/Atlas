<?php
/**
 * Created by PhpStorm.
 * User: leroy
 * Date: 9/29/2017
 * Time: 7:43 PM
 */

namespace Lorey\Atlas\Type;


use Lorey\Atlas\AtlasException;
use Lorey\Atlas\Property;
use ReflectionClass;

class Type implements IComplexType
{
    /**
     * @var ReflectionClass
     */
    private $type;

    /**
     * @var array
     */
    private $properties;

    public function __construct(ReflectionClass $type, array $properties)
    {
        $this->type = $type;
        $this->properties = $properties;
    }

    public function getName(): string
    {
        return $this->type->getShortName();
    }

    /**
     * Get the full class of this Type
     * @return string
     */
    public function getClass(): string
    {
        return $this->type->getName();
    }

    /**
     * @return Property[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function hasProperty($name): bool
    {
        return isset($this->properties[$name]);
    }

    public function getProperty($name): Property
    {
        if (!$this->hasProperty($name)) {
            $type = $this->type->name;
            throw new AtlasException("Property $$name not found in class $type");
        }

        return $this->properties[$name];
    }

    public function isPrimitive(): bool
    {
        return false;
    }
}