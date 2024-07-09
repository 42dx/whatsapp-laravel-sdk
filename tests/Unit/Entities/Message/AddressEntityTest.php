<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\Message\AddressEntity;
use The42dx\Whatsapp\Enums\ContactPropType;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class AddressEntityTest extends UnitTestCase {
    public function test_itShouldBeAnEntryInstanceObject() {
        $address = new AddressEntity([]);

        $this->assertIsObject($address);
        $this->assertInstanceOf(Entity::class, $address);
    }

    public function test_itShouldCreateObjectWithCorrectAttributes() {
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

    public function test_itShouldUpdateAttributes() {
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
}
