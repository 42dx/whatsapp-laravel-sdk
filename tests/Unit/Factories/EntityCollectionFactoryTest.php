<?php

namespace The42dx\Whatsapp\Tests\Unit\Factories;

use Illuminate\Database\Eloquent\Model;
use The42dx\Whatsapp\Entities\Message\UrlEntity;
use The42dx\Whatsapp\Factories\EntityCollectionFactory;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class Mock extends Model { }

class EntityCollectionFactoryTest extends UnitTestCase {
    public function test_itShouldThrowErrorIfProvidedClassDoesNotImplementEntityContract() {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Entity class [[]] does not implement the [The42dx\Whatsapp\Contracts\Entity] contract');

        EntityCollectionFactory::make(Mock::class, [['some' => 'prop']]);
    }

    public function test_itShouldCreateCollectionOfEntitiesCorrectly() {
        $collection = EntityCollectionFactory::make(
            UrlEntity::class,
            [
                ['url' => 'http://some.url',    'type' => 'WORK'],
                ['url' => 'http://another.url', 'type' => 'HOME']
            ]
        );

        $this->assertCount(2, $collection);
        $this->assertInstanceOf(UrlEntity::class, $collection->first());
        $this->assertInstanceOf(UrlEntity::class, $collection->last());
    }
}
