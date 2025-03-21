<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\Message\AddressEntity;
use The42dx\Whatsapp\Enums\ContactPropType;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class AddressEntityTest extends UnitTestCase {
    public function test__construct__it_should_be_an_entity_instance_object() {
        $address = new AddressEntity([]);

        $this->assertIsObject($address);
        $this->assertInstanceOf(Entity::class, $address);
    }

    public function test__construct__it_should_create_object_with_correct_attributes() {
        $addressData = $this->getJsonFixture('Api/Components/address');
        $address     = new AddressEntity($addressData);

        $this->assertIsObject($address);

        $this->assertEquals('Menlo Park', $address->city);
        $this->assertEquals('us', $address->countryCode);
        $this->assertEquals('United States', $address->country);
        $this->assertEquals('CA', $address->state);
        $this->assertEquals('1 Hacker Way', $address->street);
        $this->assertEquals(ContactPropType::WORK, $address->type);
        $this->assertEquals('94025', $address->zip);
    }

    public function test__setAttributes__it_should_update_attributes() {
        $address = new AddressEntity([]);

        $this->assertIsObject($address);

        $this->assertNull($address->city);
        $this->assertNull($address->countryCode);
        $this->assertNull($address->country);
        $this->assertNull($address->state);
        $this->assertNull($address->street);
        $this->assertNull($address->type);
        $this->assertNull($address->zip);

        $address->setAttributes([
            'city'         => 'Menlo Park',
            'country'      => 'United States',
            'country_code' => 'us',
            'state'        => 'CA',
            'street'       => '1 Hacker Way',
            'type'         => 'WORK',
            'zip'          => '94025',
        ]);

        $this->assertEquals('Menlo Park', $address->city);
        $this->assertEquals('us', $address->countryCode);
        $this->assertEquals('United States', $address->country);
        $this->assertEquals('CA', $address->state);
        $this->assertEquals('1 Hacker Way', $address->street);
        $this->assertEquals(ContactPropType::WORK, $address->type);
        $this->assertEquals('94025', $address->zip);
    }

    public function test__toArray__it_should_convert_to_array_correctly() {
        $addressData = $this->getJsonFixture('Api/Components/address');
        $address     = new AddressEntity($addressData);
        $array     = $address->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('city', $array);
        $this->assertArrayHasKey('countryCode', $array);
        $this->assertArrayHasKey('country', $array);
        $this->assertArrayHasKey('state', $array);
        $this->assertArrayHasKey('street', $array);
        $this->assertArrayHasKey('type', $array);
        $this->assertArrayHasKey('zip', $array);
    }

    public function test__toJson__it_should_convert_to_json_correctly() {
        $addressData = $this->getJsonFixture('Api/Components/address');
        $address     = new AddressEntity($addressData);
        $json        = $address->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('city', $json);
        $this->assertStringContainsString('countryCode', $json);
        $this->assertStringContainsString('country', $json);
        $this->assertStringContainsString('state', $json);
        $this->assertStringContainsString('street', $json);
        $this->assertStringContainsString('type', $json);
        $this->assertStringContainsString('zip', $json);
    }
}
