<?php

namespace Lorey\Atlas\Type;

interface IType
{
    public function isPrimitive(): bool;

    public function getName(): string;
}