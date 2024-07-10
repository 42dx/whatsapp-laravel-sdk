<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\Message\LocationEntity;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class LocationEntityTest extends UnitTestCase {
    public function test_itShouldBeAnEntryInstanceObject() {
        $location = new LocationEntity([]);

        $this->assertIsObject($location);
        $this->assertInstanceOf(Entity::class, $location);
    }

    public function test_itShouldCreateObjectWithCorrectAttributes() {
        $locationData = $this->getJsonFixture('Api/Components/location');
        $location     = new LocationEntity($locationData);

        $this->assertIsObject($location);

        $this->assertEquals('Main Street Beach, Santa Cruz, CA', $location->address);
        $this->assertEquals(38.9806263495, $location->latitude);
        $this->assertEquals(-131.9428612257, $location->longitude);
        $this->assertEquals('Main Street Beach', $location->name);
        $this->assertEquals('https://foursquare.com/v/4d7031d35b5df7744', $location->url);
    }

    public function test_itShouldUpdateAttributes() {
        $location = new LocationEntity([]);

        $this->assertIsObject($location);

        $this->assertNull($location->address);
        $this->assertNull($location->latitude);
        $this->assertNull($location->longitude);
        $this->assertNull($location->name);
        $this->assertNull($location->url);

        $location->setAttributes([
            'address'   => 'Main Street Beach, Santa Cruz, CA',
            'latitude'  => 38.9806263495,
            'longitude' => -131.9428612257,
            'name'      => 'Main Street Beach',
            'url'       => 'https://foursquare.com/v/4d7031d35b5df7744',
        ]);

        $this->assertEquals('Main Street Beach, Santa Cruz, CA', $location->address);
        $this->assertEquals(38.9806263495, $location->latitude);
        $this->assertEquals(-131.9428612257, $location->longitude);
        $this->assertEquals('Main Street Beach', $location->name);
        $this->assertEquals('https://foursquare.com/v/4d7031d35b5df7744', $location->url);
    }

    public function test_itShouldConvertToArrayCorrectly() {
        $locationData = $this->getJsonFixture('Api/Components/location');
        $location     = new LocationEntity($locationData);
        $array     = $location->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('address', $array);
        $this->assertArrayHasKey('latitude', $array);
        $this->assertArrayHasKey('longitude', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('url', $array);
    }

    public function test_itShouldConvertToJsonCorrectly() {
        $locationData = $this->getJsonFixture('Api/Components/location');
        $location     = new LocationEntity($locationData);
        $json         = $location->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('address', $json);
        $this->assertStringContainsString('latitude', $json);
        $this->assertStringContainsString('longitude', $json);
        $this->assertStringContainsString('name', $json);
        $this->assertStringContainsString('url', $json);
    }
}
