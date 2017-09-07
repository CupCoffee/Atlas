# Atlas

Atlas allows you to map your classes and analyze the type of each property.

## Getting Started
### Installing
You can install Atlas using composer:

```
composer require lorey/atlas
```

### Using Atlas
Atlas analyzes your class using docblocks with your properties.

```php
<?php

class Person {
	/**
         * @var string
         */
        public $name;
    
        /**
         * @var Sex?
         */
        public $sex;
                
        /**
         * @var int
         */
        public $age;
    
        /**
         * @var Person[]
         */
        public $siblings;
}
```

```php
$atlas = Atlas::map(Person::class);
$property = $atlas->getProperty('name');
$type = $property->getType();
```

### Properties
Atlas enables you to specify different kinds of information about your properties.

#### Type
Using the @var tag you can define the type of the property
```php

class Family {
    /**
    * @var string
    */
    public $name;
}

```

#### Array
Atlas will mark the property as an array when the type is suffixed with a []
```php

class Family {
    /**
    * @var People[]
    */
    public $members;
}

```

#### Nullable
Atlas will mark the property as nullable when the type is suffixed with a ?
```php

class Family {
    /**
    * @var People[]
    */
    public $members;
}

```

## Running the tests
Tests are implemented using PHPUnit

```bash
./bin/phpunit
```

## Built with
- PHPUnit

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

## Acknowledgments

* This package was written primarily for [Reify](https://github.com/cupcoffee/reify), interested in mapping JSON to objects? [Try it for yourself!](https://github.com/cupcoffee/reify)
