<?php

namespace Lorey\Atlas\Tests;


use Lorey\Atlas\Atlas;
use Lorey\Atlas\Tests\Object\Elder;
use PHPUnit\Framework\TestCase;

class RecursiveAtlasTest extends TestCase
{
    public function test_maps_types_recursively()
    {
        $atlas = Atlas::resolve(Elder::class);
        $this->assertTrue($atlas->getProperty('children')->getType()->getProperty('elder')->isClass(Elder::class));
    }
}