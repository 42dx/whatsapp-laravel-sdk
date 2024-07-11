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

    public function test__construct__it_should_create_object_with_correct_attributes() {
        $eventData = $this->getJsonFixture('Api/Components/event');
        $event     = new EventEntity($eventData);

        $this->assertIsObject($event);

        $this->assertEquals(ObjectType::WPP_BUSINESS_API_ACC, $event->object);

        $this->assertInstanceOf(Collection::class, $event->entry);
        $this->assertEquals(1, $event->entry->count());
        $this->assertInstanceOf(EntryEntity::class, $event->entry->first());
    }

    public function test__setAttributes__it_should_update_attributes() {
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

    public function test__toArray__it_should_convert_to_array_correctly() {
        $eventData = $this->getJsonFixture('Api/Components/event');
        $event     = new EventEntity($eventData);
        $array     = $event->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('object', $array);
        $this->assertArrayHasKey('entry', $array);
    }

    public function test__toJson__it_should_convert_to_json_correctly() {
        $eventData = $this->getJsonFixture('Api/Components/event');
        $event     = new EventEntity($eventData);
        $json      = $event->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('object', $json);
        $this->assertStringContainsString('entry', $json);
    }
}
