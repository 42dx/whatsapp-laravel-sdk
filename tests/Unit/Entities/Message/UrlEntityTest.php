<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\Message\UrlEntity;
use The42dx\Whatsapp\Enums\ContactPropType;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class UrlEntityTest extends UnitTestCase {
    public function test_itShouldBeAnEntryInstanceObject() {
        $url = new UrlEntity([]);

        $this->assertIsObject($url);
        $this->assertInstanceOf(Entity::class, $url);
    }

    public function test_itShouldCreateObjectWithCorrectAttributes() {
        $urlData = $this->getJsonFixture('Api/Components/url');
        $url     = new UrlEntity($urlData);

        $this->assertIsObject($url);

        $this->assertEquals('https://some.url', $url->url);
        $this->assertEquals(ContactPropType::WORK, $url->type);
    }

    public function test_itShouldUpdateAttributes() {
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
}
