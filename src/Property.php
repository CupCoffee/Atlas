<?php

namespace Lorey\Atlas;


use GuzzleHttp\Promise\Promise;
use Lorey\Atlas\Type\IComplexType;
use Lorey\Atlas\Type\IType;
use Lorey\Atlas\Type\Type;

class Property
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Type
     */
    private $type;

    /**
     * @var bool
     */
    private $nullable;

    /**
     * @var bool
     */
    private $isArray;

    /**
     * Property constructor.
     * @param string $name
     * @param IType|Promise $type
     * @param bool $nullable
     * @param bool $isArray
     */
    public function __construct(string $name, $type, bool $nullable, bool $isArray)
    {
        if ($type instanceof Promise) {
            $type->then(function($result) {
                $this->type = $result;
            });
        } else {
            $this->type = $type;
        }

        $this->name = $name;
        $this->nullable = $nullable;
        $this->isArray = $isArray;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return IType|IComplexType
     */
    public function getType(): IType
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @return bool
     */
    public function isArray(): bool
    {
        return $this->isArray;
    }

    public function isClass(string $other): bool
    {
        return $this->type->getClass() === $other;
    }
}