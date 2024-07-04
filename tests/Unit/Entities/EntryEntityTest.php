<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities;

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Entities\{ChangesEntity, EntryEntity};
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class EntryEntityTest extends UnitTestCase {
    public function test_itShouldBeAnEntryInstanceObject() {
        $entry = new EntryEntity([]);

        $this->assertIsObject($entry);
        $this->assertInstanceOf(Entity::class, $entry);
    }

    public function test_itShouldCreateObjectWithCorrectAttributes() {
        $entryData = $this->getJsonFixture('Api/Components/entry');
        $entry     = new EntryEntity($entryData);

        $this->assertIsObject($entry);

        $this->assertNotNull($entry->id);
        $this->assertEquals('123', $entry->id);

        $this->assertNotNull($entry->changes);
        $this->assertInstanceOf(Collection::class, $entry->changes);
        $this->assertEquals(1, $entry->changes->count());
        $this->assertInstanceOf(ChangesEntity::class, $entry->changes->first());
    }

    public function test_itShouldUpdateAttributes() {
        $entryId = '456';
        $entry = new EntryEntity([]);

        $this->assertNull($entry->id);
        $this->assertNull($entry->changes);

        $entry->setAttributes([
            'id'      => $entryId,
            'changes' => [[]]
        ]);

        $this->assertNotNull($entry->id);
        $this->assertEquals($entryId, $entry->id);

        $this->assertNotNull($entry->changes);
        $this->assertInstanceOf(Collection::class, $entry->changes);
        $this->assertEquals(1, $entry->changes->count());
        $this->assertInstanceOf(ChangesEntity::class, $entry->changes->first());
    }
}
