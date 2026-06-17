<?php

namespace The42dx\Whatsapp\Tests\Unit\Factories;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use The42dx\Whatsapp\Entities\Message\UrlEntity;
use The42dx\Whatsapp\Factories\EntityCollection;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class Mock extends Model {}

class EntityCollectionTest extends UnitTestCase {
    public function test__make__it_should_throw_error_if_provided_class_does_not_implement_entity_contract(): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Entity class [[]] does not implement the [The42dx\Whatsapp\Contracts\Entity] contract');

        EntityCollection::make(Mock::class, [['some' => 'prop']]);
    }

    public function test__make__it_should_create_collection_of_entities_correctly(): void {
        $collection = EntityCollection::make(
            UrlEntity::class,
            [
                ['url' => 'https://some.url',    'type' => 'WORK'],
                ['url' => 'https://another.url', 'type' => 'HOME'],
            ]
        );

        $this->assertCount(2, $collection);
        $this->assertInstanceOf(UrlEntity::class, $collection->first());
        $this->assertInstanceOf(UrlEntity::class, $collection->last());
    }
}
