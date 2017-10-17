<?php

namespace Lorey\Atlas;

use GuzzleHttp\Promise\Promise;
use Lorey\Atlas\Type\IType;
use Lorey\Atlas\Type\PrimitiveType;
use Lorey\Atlas\Type\Type;
use ReflectionClass;

class TypeFactory
{
    const PROPERTY_TYPE_PATTERN = "/@var\\s*([^\\s]+)/";

    private static $resolvedTypes = [];
    private static $resolved = [];

    /**
     * @param string $type
     * @return IType|Promise
     */
    public function build(string $type)
    {
        $key = $type;

        if (!isset(self::$resolvedTypes[$type])) {
            self::$resolvedTypes[$type] = (new Promise())->then(function($result) use($type) {
                self::$resolvedTypes[$type] = $result;
            });

            $reflectionClass = new ReflectionClass($type);
            $properties = $this->mapProperties($reflectionClass);

            $type = new Type($reflectionClass, $properties);

            self::$resolvedTypes[$key]->resolve($type);
        }

        return self::$resolvedTypes[$key];
    }

    public function buildPropertyType(string $type, string $path = "")
    {
        $key = "$path\\$type";

        if (!isset(self::$resolved[$key])) {
            $type = $this->build($type);

            if ($type instanceof Promise) {
                $type->then(function($result) use ($key) {
                    self::$resolved[$key] = $result;
                });
            } else {
                self::$resolved[$key] = $type;
            }

            return $type;
        }


        return self::$resolved[$key];
    }

    /**
     * @param $name
     * @param $type
     * @param ReflectionClass $owner
     * @return Property
     */
    private function buildProperty($name, $type, ReflectionClass $owner): Property
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

        if (TypeHelper::isPrimitive($type)) {
            $type = new PrimitiveType($type);
        } else {
            $namespace = str_replace("\\" . $owner->getShortName(), "", $owner->getName());
            $type = $this->buildPropertyType($this->resolveClass($type, $namespace));
        }

        return new Property($name, $type, $isNullable, $isArray);
    }

    /**
     * @param ReflectionClass $reflectedType
     * @return Property[]
     */
    private function mapProperties(ReflectionClass $reflectedType): array
    {
        $properties = [];

        foreach($reflectedType->getProperties() as $property) {
            $matches = [];
            preg_match(static::PROPERTY_TYPE_PATTERN, $property->getDocComment(), $matches);

            if ($matches) {
                $properties[$property->getName()] = $this->buildProperty($property->getName(), $matches[1], $reflectedType);
            }
        }

        return $properties;
    }

    private function resolveClass($type, $namespace = null)
    {
        if (class_exists($type)) {
            return $type;
        }

        if ($namespace) {
            $absoluteClassPath = "$namespace\\$type";

            if (class_exists($absoluteClassPath)) {
                return $absoluteClassPath;
            }
        }

        $foundClasses = array_values(array_filter(get_declared_classes(), function($class) use ($type) {
            return strpos($class, $type) !== false;
        }));

        if (count($foundClasses) === 1) {
            return $foundClasses[0];
        }

        throw new AtlasException("Couldn't resolve $type to a existing class");
    }
}