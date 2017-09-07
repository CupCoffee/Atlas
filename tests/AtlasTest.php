<?php

namespace Lorey\Atlas\Tests;

use Lorey\Atlas\Atlas;
use Lorey\Atlas\Property;
use Lorey\Atlas\Tests\Object\Family;
use Lorey\Atlas\Tests\Object\Person;
use PHPUnit\Framework\TestCase;

class AtlasTest extends TestCase
{
	public function testMapsPrimitiveProperties()
	{
		$atlas = Atlas::map(Person::class);

		$this->assertEquals('string', $atlas->getProperty('name')->getType());
		$this->assertEquals('int', $atlas->getProperty('age')->getType());
		$this->assertEquals('bool', $atlas->getProperty('isMarried')->getType());
	}

	public function testMapCollectionProperty()
	{
		$atlas = Atlas::map(Family::class);

		$this->assertTrue($atlas->getProperty('members')->isArray());
	}

	public function testMapsNullableProperties()
	{
		$atlas = Atlas::map(Family::class);

		$this->assertTrue($atlas->getProperty('isRoyal')->isNullable());
	}

	public function testMapsInstances()
	{
		$atlas = Atlas::map(new Person());

		$this->assertEquals('string', $atlas->getProperty('name')->getType());
		$this->assertEquals('int', $atlas->getProperty('age')->getType());
		$this->assertEquals('bool', $atlas->getProperty('isMarried')->getType());
	}

	public function testMapsPrivateProperties()
	{
		$atlas = Atlas::map(Family::class);

		$this->assertInstanceOf(Property::class, $atlas->getProperty('isRoyal'));
	}
}