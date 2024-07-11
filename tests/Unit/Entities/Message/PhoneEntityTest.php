<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\Message\PhoneEntity;
use The42dx\Whatsapp\Enums\ContactPropType;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class PhoneEntityTest extends UnitTestCase {
    public function test__construct__it_should_be_an_entity_instance_object() {
        $phone = new PhoneEntity([]);

        $this->assertIsObject($phone);
        $this->assertInstanceOf(Entity::class, $phone);
    }

    public function test__construct__it_should_create_object_with_correct_attributes() {
        $phoneData = $this->getJsonFixture('Api/Components/phone');
        $phone     = new PhoneEntity($phoneData);

        $this->assertIsObject($phone);

        $this->assertEquals('5541999999999', $phone->number);
        $this->assertEquals(ContactPropType::WORK, $phone->type);
        $this->assertEquals('123123123', $phone->waId);
    }

    public function test__setAttributes__it_should_update_attributes() {
        $phone = new PhoneEntity([]);

        $this->assertIsObject($phone);

        $this->assertNull($phone->number);
        $this->assertNull($phone->type);
        $this->assertNull($phone->waId);

        $phone->setAttributes([
            'phone' => '5541999999999',
            'type'  => ContactPropType::WORK->value,
            'wa_id' => '123123123',
        ]);

        $this->assertEquals('5541999999999', $phone->number);
        $this->assertEquals(ContactPropType::WORK, $phone->type);
        $this->assertEquals('123123123', $phone->waId);
    }

    public function test__toArray__it_should_convert_to_array_correctly() {
        $phoneData = $this->getJsonFixture('Api/Components/phone');
        $phone     = new PhoneEntity($phoneData);
        $array     = $phone->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('number', $array);
        $this->assertArrayHasKey('type', $array);
        $this->assertArrayHasKey('waId', $array);
    }

    public function test__toJson__it_should_convert_to_json_correctly() {
        $phoneData = $this->getJsonFixture('Api/Components/phone');
        $phone     = new PhoneEntity($phoneData);
        $json      = $phone->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('number', $json);
        $this->assertStringContainsString('type', $json);
        $this->assertStringContainsString('waId', $json);
    }
}
