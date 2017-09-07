<?php

namespace Lorey\Atlas;


class Property
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
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
     * @param $name
     * @param $type
     * @param $nullable
     * @param $isArray
     */
    public function __construct($name, $type, $nullable, $isArray)
    {
        $this->name = $name;
        $this->type = $type;
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
     * @return string
     */
    public function getType()
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

    public function isType(string $other): bool
    {
        return $this->type === $other;
    }
}