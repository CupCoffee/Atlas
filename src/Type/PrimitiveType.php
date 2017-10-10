<?php

namespace Lorey\Atlas\Type;

class PrimitiveType implements IType
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {

        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isPrimitive(): bool
    {
        return true;
    }
}