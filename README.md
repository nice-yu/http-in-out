
# http-in-out Library

> [简体中文](README.zh-CN.md) | [English](README.md)
> 
## Unit Tests

```
PHPUnit 9.6.20 by Sebastian Bergmann and contributors.

Runtime:       PHP 7.4
Configuration: /var/www/packages/http-in-out/phpunit.xml

Dto (NiceYu\Tests\Dto)
 ✔ Item dto [2.94 ms]
 ✔ Success dto [3.67 ms]
 ✔ Array dto [126.18 ms]
 ✔ Array dto initialization [1.34 ms]
 ✔ Array dto serialization [3.82 ms]
 ✔ Array dto for objects [6.24 ms]

Time: 00:00.156, Memory: 8.00 MB

OK (6 tests, 45 assertions)
```

## Installation

### System Requirements
- PHP >= 7.4
- jms/serializer >= 3.0

### Install Composer

If you don't have Composer installed, first install it:
```sh
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

### Install Library

```sh
composer require nice-yu/http-in-out
```

## Define DTO Objects

```php
<?php
declare(strict_types=1);

namespace NiceYu\HttpInOut\Dto;

use NiceYu\HttpInOut\AbstractDtoTransformer;

class ItemDto extends AbstractDtoTransformer
{
    public string $name;
    public string $value;
}
```

## Using DTO

### Initialize and Use `ItemDto`

```php
<?php
declare(strict_types=1);

use NiceYu\HttpInOut\Dto\ItemDto;

$item = new ItemDto();
$item->name = '123';
$item->value = 'http-in-out';

echo $item->serialize(); // {"name":"123","value":"http-in-out"}
print_r($item->toArray()); // ["name" => "123", "value" => "http-in-out"]
echo $item->toResponse(); // {"code":0,"message":"ok","result":{"name":"123","value":"http-in-out"}}
```

### Using `ArrayDto` to Process Multiple Data

```php
<?php
declare(strict_types=1);

use NiceYu\HttpInOut\Dto\ArrayDto;
use NiceYu\HttpInOut\Dto\ItemDto;

$data = [
    ['id' => 1, 'name' => 'test1'],
    ['id' => 2, 'name' => 'test2'],
    ['id' => 3, 'name' => 'test3'],
    ['id' => 4, 'name' => 'test4'],
];

$array = new ArrayDto();
$array->items = $array->forObjects($data, function ($item) {
    $o = new ItemDto();
    $o->name = (string)$item['id'];
    $o->value = $item['name'];
    return $o;
});

echo $array->serialize(); // Serialize
print_r($array->toArray()); // Convert to Array
echo $array->toResponse(); // Convert to Response
```

## Error Handling

- Note the initialization values of the classes; if they are null, they will not be serialized.

## License

This project is licensed under the [MIT License](LICENSE).
