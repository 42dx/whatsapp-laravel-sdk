<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities;

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\{EntryEntity, EventEntity};
use The42dx\Whatsapp\Enums\ObjectType;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class EventEntityTest extends UnitTestCase {
    public function test_itShouldBeAnEntityInstanceObject() {
        $event = new EventEntity([]);

        $this->assertIsObject($event);
        $this->assertInstanceOf(Entity::class, $event);
    }

    public function test_itShouldCreateObjectWithCorrectAttributes() {
        $eventData = $this->getJsonFixture('Api/Components/event');
        $event     = new EventEntity($eventData);

        $this->assertIsObject($event);

        $this->assertEquals(ObjectType::WPP_BUSINESS_API_ACC, $event->object);

        $this->assertInstanceOf(Collection::class, $event->entry);
        $this->assertEquals(1, $event->entry->count());
        $this->assertInstanceOf(EntryEntity::class, $event->entry->first());
    }

    public function test_itShouldUpdateAttributes() {
        $event = new EventEntity([]);

        $this->assertNull($event->object);
        $this->assertNull($event->entry);

        $event->setAttributes([
            'object' => ObjectType::WPP_BUSINESS_API_ACC->value,
            'entry'  => [[]]
        ]);

        $this->assertEquals(ObjectType::WPP_BUSINESS_API_ACC, $event->object);

        $this->assertInstanceOf(Collection::class, $event->entry);
        $this->assertEquals(1, $event->entry->count());
        $this->assertInstanceOf(EntryEntity::class, $event->entry->first());
    }

    public function test_itShouldConvertToArrayCorrectly() {
        $eventData = $this->getJsonFixture('Api/Components/event');
        $event     = new EventEntity($eventData);
        $array     = $event->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('object', $array);
        $this->assertArrayHasKey('entry', $array);
    }

    public function test_itShouldConvertToJsonCorrectly() {
        $eventData = $this->getJsonFixture('Api/Components/event');
        $event     = new EventEntity($eventData);
        $json      = $event->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('object', $json);
        $this->assertStringContainsString('entry', $json);
    }
}
