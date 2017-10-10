<?php

namespace Lorey\Atlas;

use GuzzleHttp\Promise\Promise;
use InvalidArgumentException;
use Lorey\Atlas\Type\IComplexType;
use Lorey\Atlas\Type\IType;

class Atlas
{
    /**
     * @param $type
     * @return IType
     * @deprecated
     */
    public static function map($type)
    {
        return static::resolve($type);
    }

    /**
     * @param $type
     * @return IType|IComplexType
     */
    public static function resolve($type): IType
    {
        if (!is_object($type) && !class_exists($type)) {
            throw new InvalidArgumentException("Type $type does not exist");
        }

        if (is_object($type)) {
            $type = get_class($type);
        }

        $type = (new TypeFactory())->build($type);

        if ($type instanceof Promise) {
            return $type->wait();
        }

        return $type;
    }
}
