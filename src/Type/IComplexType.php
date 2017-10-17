<?php

namespace Lorey\Atlas\Type;


use Lorey\Atlas\Property;

interface IComplexType extends IType
{
    /**
     * @return Property[]
     */
    public function getProperties(): array;

    public function hasProperty($name): bool;

    public function getProperty($name): Property;
}