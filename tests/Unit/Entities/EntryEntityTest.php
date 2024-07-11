<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities;

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Entities\{ChangesEntity, EntryEntity};
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class EntryEntityTest extends UnitTestCase {
    public function test__construct__it_should_be_an_entity_instance_object() {
        $entry = new EntryEntity([]);

        $this->assertIsObject($entry);
        $this->assertInstanceOf(Entity::class, $entry);
    }

    public function test__construct__it_should_create_object_with_correct_attributes() {
        $entryData = $this->getJsonFixture('Api/Components/entry');
        $entry     = new EntryEntity($entryData);

        $this->assertIsObject($entry);

        $this->assertEquals('123', $entry->id);

        $this->assertInstanceOf(Collection::class, $entry->changes);
        $this->assertEquals(1, $entry->changes->count());
        $this->assertInstanceOf(ChangesEntity::class, $entry->changes->first());
    }

    public function test__setAttributes__it_should_update_attributes() {
        $entryId = '456';
        $entry = new EntryEntity([]);

        $this->assertNull($entry->id);
        $this->assertNull($entry->changes);

        $entry->setAttributes([
            'id'      => $entryId,
            'changes' => [[]]
        ]);

        $this->assertEquals($entryId, $entry->id);

        $this->assertInstanceOf(Collection::class, $entry->changes);
        $this->assertEquals(1, $entry->changes->count());
        $this->assertInstanceOf(ChangesEntity::class, $entry->changes->first());
    }

    public function test__toArray__it_should_convert_to_array_correctly() {
        $entryData = $this->getJsonFixture('Api/Components/entry');
        $entry     = new EntryEntity($entryData);
        $array     = $entry->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('changes', $array);
    }

    public function test__toJson__it_should_convert_to_json_correctly() {
        $entryData = $this->getJsonFixture('Api/Components/entry');
        $entry     = new EntryEntity($entryData);
        $json      = $entry->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('id', $json);
        $this->assertStringContainsString('changes', $json);
    }
}
