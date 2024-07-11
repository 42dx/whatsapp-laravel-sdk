<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\Message\UrlEntity;
use The42dx\Whatsapp\Enums\ContactPropType;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class UrlEntityTest extends UnitTestCase {
    public function test__construct_it_should_be_an_entity_instance_object() {
        $url = new UrlEntity([]);

        $this->assertIsObject($url);
        $this->assertInstanceOf(Entity::class, $url);
    }

    public function test__construct_it_should_create_object_with_correct_attributes() {
        $urlData = $this->getJsonFixture('Api/Components/url');
        $url     = new UrlEntity($urlData);

        $this->assertIsObject($url);

        $this->assertEquals('https://some.url', $url->url);
        $this->assertEquals(ContactPropType::WORK, $url->type);
    }

    public function test__setAttributes_it_should_update_attributes() {
        $url = new UrlEntity([]);

        $this->assertIsObject($url);

        $this->assertNull($url->url);
        $this->assertNull($url->type);

        $url->setAttributes([
            'url' => 'https://some.url',
            'type' => ContactPropType::HOME->value,
        ]);

        $this->assertEquals('https://some.url', $url->url);
        $this->assertEquals(ContactPropType::HOME, $url->type);
    }

    public function test__toArray_it_should_convert_to_array_correctly() {
        $urlData = $this->getJsonFixture('Api/Components/url');
        $url     = new UrlEntity($urlData);
        $array   = $url->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('url', $array);
        $this->assertArrayHasKey('type', $array);
    }

    public function test__toJson_it_should_convert_to_json_correctly() {
        $urlData = $this->getJsonFixture('Api/Components/url');
        $url     = new UrlEntity($urlData);
        $json    = $url->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('url', $json);
        $this->assertStringContainsString('type', $json);
    }
}
