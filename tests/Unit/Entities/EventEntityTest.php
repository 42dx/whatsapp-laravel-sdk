<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities;

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\{EntryEntity, EventEntity};
use The42dx\Whatsapp\Enums\ObjectType;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class EventEntityTest extends UnitTestCase {
    public function test_it_should_be_an_entity_instance_object(): void {
        $event = new EventEntity([]);

        $this->assertIsObject($event);
        $this->assertInstanceOf(Entity::class, $event);
    }

    public function test__construct__it_should_create_object_with_correct_attributes(): void {
        $eventData = self::getJsonFixture('Api/Components/event');
        $event = new EventEntity($eventData);

        $this->assertIsObject($event);

        $this->assertEquals(ObjectType::WHATSAPP_BUSINESS_API_ACC, $event->object);

        $this->assertInstanceOf(Collection::class, $event->entries);
        $this->assertEquals(1, $event->entries->count());
        $this->assertInstanceOf(EntryEntity::class, $event->entries->first());
    }

    public function test__set_attributes__it_should_update_attributes(): void {
        $event = new EventEntity([]);

        $this->assertNull($event->object);
        $this->assertNull($event->entries);

        $event->setAttributes([
            'object' => ObjectType::WHATSAPP_BUSINESS_API_ACC->value,
            'entry' => [[]],
        ]);

        $this->assertEquals(ObjectType::WHATSAPP_BUSINESS_API_ACC, $event->object);

        $this->assertInstanceOf(Collection::class, $event->entries);
        $this->assertEquals(1, $event->entries->count());
        $this->assertInstanceOf(EntryEntity::class, $event->entries->first());
    }

    public function test__to_array__it_should_convert_to_array_correctly(): void {
        $eventData = self::getJsonFixture('Api/Components/event');
        $event = new EventEntity($eventData);
        $array = $event->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('object', $array);
        $this->assertArrayHasKey('entries', $array);
    }

    public function test__to_json__it_should_convert_to_json_correctly(): void {
        $eventData = self::getJsonFixture('Api/Components/event');
        $event = new EventEntity($eventData);
        $json = $event->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('object', $json);
        $this->assertStringContainsString('entries', $json);
    }
}
