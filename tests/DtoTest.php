<?php
declare(strict_types=1);

namespace NiceYu\Tests;

use NiceYu\HttpInOut\Dto\ItemDto;
use NiceYu\HttpInOut\Dto\SuccessDto;
use NiceYu\HttpInOut\Dto\ArrayDto;
use PHPUnit\Framework\TestCase;

/**
 * @covers \NiceYu\HttpInOut\Dto\ItemDto
 * @covers \NiceYu\HttpInOut\Dto\ArrayDto
 * @covers \NiceYu\HttpInOut\Dto\SuccessDto
 * @covers \NiceYu\HttpInOut\AbstractDtoTransformer
 */
class DtoTest extends TestCase
{
    public function testItemDto()
    {
        $itemDto = new ItemDto();
        $itemDto->name = 'Test';
        $itemDto->value = 'Value';

        $this->assertEquals('Test', $itemDto->name);
        $this->assertEquals('Value', $itemDto->value);

        $serialized = $itemDto->serialize();
        $this->assertJson($serialized);

        $array = $itemDto->toArray();
        $this->assertIsArray($array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('value', $array);

        $response = $itemDto->toResponse(200, 'Success');
        $this->assertJson($response);
    }

    public function testSuccessDto()
    {
        $successDto = new SuccessDto();
        $serialized = $successDto->serialize();
        $this->assertJson($serialized);

        $array = $successDto->toArray();
        $this->assertIsArray($array);

        $response = $successDto->toResponse(200, 'Success');
        $this->assertJson($response);
    }

    public function testArrayDto()
    {
        $data = [
            ['id'=>1, 'name'=>'test1'],
            ['id'=>2, 'name'=>'test2'],
            ['id'=>3, 'name'=>'test3'],
            ['id'=>4, 'name'=>'test4'],
        ];
        $arrayDto = new ArrayDto();
        $arrayDto->items = $arrayDto->forObjects($data, function($item) {
            $o = new ItemDto();
            $o->name = (string)$item['id'];
            $o->value = $item['name'];
            return $o;
        });

        $this->assertCount(4, $arrayDto->items);
        $this->assertEquals('1', $arrayDto->items[0]->name);
        $this->assertEquals('2', $arrayDto->items[1]->name);

        $this->assertEquals('test1', $arrayDto->items[0]->value);
        $this->assertEquals('test2', $arrayDto->items[1]->value);

        $serialized = $arrayDto->serialize();
        $this->assertJson($serialized);

        $array = $arrayDto->toArray();
        $this->assertIsArray($array);
        $this->assertArrayHasKey('items', $array);
        $this->assertArrayHasKey('name', $array['items'][0]);
        $this->assertArrayHasKey('value', $array['items'][0]);

        $response = $arrayDto->toResponse(200, 'Success');
        $this->assertJson($response);
    }
    public function testArrayDtoInitialization()
    {
        $arrayDto = new ArrayDto();
        $this->assertIsArray($arrayDto->items);
        $this->assertEmpty($arrayDto->items);
    }

    public function testArrayDtoSerialization()
    {
        $arrayDto = new ArrayDto();
        $item1 = new ItemDto();
        $item1->name = 'Item 1';
        $item1->value = 'Value 1';

        $item2 = new ItemDto();
        $item2->name = 'Item 2';
        $item2->value = 'Value 2';

        $arrayDto->items = [$item1, $item2];

        $serialized = $arrayDto->serialize();
        $this->assertJson($serialized);

        $array = $arrayDto->toArray();
        $this->assertIsArray($array);
        $this->assertCount(2, $array['items']);
        $this->assertEquals('Item 1', $array['items'][0]['name']);
        $this->assertEquals('Value 1', $array['items'][0]['value']);

        $response = $arrayDto->toResponse(200, 'Success');
        $this->assertJson($response);
        $responseData = json_decode($response, true);
        $this->assertEquals(200, $responseData['code']);
        $this->assertEquals('Success', $responseData['message']);
        $this->assertCount(2, $responseData['result']['items']);
    }

    public function testArrayDtoForObjects()
    {
        $data = [
            ['id' => 1, 'name' => 'test1'],
            ['id' => 2, 'name' => 'test2'],
            ['id' => 3, 'name' => 'test3'],
            ['id' => 4, 'name' => 'test4'],
        ];

        $arrayDto = new ArrayDto();
        $arrayDto->items = $arrayDto->forObjects($data, function ($item) {
            $o = new ItemDto();
            $o->name = (string)$item['id'];
            $o->value = $item['name'];
            return $o;
        });

        $this->assertCount(4, $arrayDto->items);
        $this->assertInstanceOf(ItemDto::class, $arrayDto->items[0]);
        $this->assertEquals('1', $arrayDto->items[0]->name);
        $this->assertEquals('test1', $arrayDto->items[0]->value);

        $serialized = $arrayDto->serialize();
        $this->assertJson($serialized);

        $array = $arrayDto->toArray();
        $this->assertIsArray($array);
        $this->assertCount(4, $array['items']);
        $this->assertEquals('1', $array['items'][0]['name']);
        $this->assertEquals('test1', $array['items'][0]['value']);

        $response = $arrayDto->toResponse(200, 'Success');
        $this->assertJson($response);
        $responseData = json_decode($response, true);
        $this->assertEquals(200, $responseData['code']);
        $this->assertEquals('Success', $responseData['message']);
        $this->assertCount(4, $responseData['result']['items']);
    }
}
