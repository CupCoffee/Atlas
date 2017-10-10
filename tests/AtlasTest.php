<?php

namespace Lorey\Atlas\Tests;

use Lorey\Atlas\Atlas;
use Lorey\Atlas\Property;
use Lorey\Atlas\Tests\Object\Family;
use Lorey\Atlas\Tests\Object\Person;
use PHPUnit\Framework\TestCase;

class AtlasTest extends TestCase
{
    public function test_maps_type_name()
    {
        $atlas = Atlas::map(Person::class);
        $this->assertEquals('Person', $atlas->getName());
    }

	public function test_maps_primitive_properties()
	{
		$atlas = Atlas::map(Person::class);

		$this->assertEquals('string', $atlas->getProperty('name')->getType()->getName());
		$this->assertEquals('int', $atlas->getProperty('age')->getType()->getName());
		$this->assertEquals('bool', $atlas->getProperty('isMarried')->getType()->getName());
	}

	public function test_maps_collection_properties()
	{
		$atlas = Atlas::map(Family::class);

		$this->assertTrue($atlas->getProperty('members')->isArray());
	}

	public function test_maps_nullable_properties()
	{
		$atlas = Atlas::map(Family::class);

		$this->assertTrue($atlas->getProperty('isRoyal')->isNullable());
	}

	public function test_maps_instances()
	{
		$atlas = Atlas::map(new Person());

		$this->assertEquals('string', $atlas->getProperty('name')->getType()->getName());
		$this->assertEquals('int', $atlas->getProperty('age')->getType()->getName());
		$this->assertEquals('bool', $atlas->getProperty('isMarried')->getType()->getName());
	}

	public function test_maps_private_properties()
	{
		$atlas = Atlas::map(Family::class);

		$this->assertInstanceOf(Property::class, $atlas->getProperty('isRoyal'));
	}
}