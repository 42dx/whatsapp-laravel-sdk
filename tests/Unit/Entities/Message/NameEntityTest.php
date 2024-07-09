<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\Message\NameEntity;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class NameEntityTest extends UnitTestCase {
    public function test_itShouldBeAnEntryInstanceObject() {
        $name = new NameEntity([]);

        $this->assertIsObject($name);
        $this->assertInstanceOf(Entity::class, $name);
    }

    public function test_itShouldCreateObjectWithCorrectAttributes() {
        $nameData = $this->getJsonFixture('Api/Components/name');
        $name     = new NameEntity($nameData);

        $this->assertIsObject($name);

        $this->assertEquals('John', $name->first);
        $this->assertEquals('Mr. John Doe Jr.', $name->formatted);
        $this->assertEquals('Doe', $name->last);
        $this->assertEquals('Whatever', $name->middle);
        $this->assertEquals('Mr.', $name->prefix);
        $this->assertEquals('Jr.', $name->suffix);
    }

    public function test_itShouldUpdateAttributes() {
        $name = new NameEntity([]);

        $this->assertIsObject($name);

        $this->assertNull($name->first);
        $this->assertNull($name->formatted);
        $this->assertNull($name->last);
        $this->assertNull($name->middle);
        $this->assertNull($name->prefix);
        $this->assertNull($name->suffix);

        $name->setAttributes([
            'first_name'     => 'John',
            'formatted_name' => 'Mr. John Doe Jr.',
            'last_name'      => 'Doe',
            'middle_name'    => 'Whatever',
            'name-prefix'    => 'Mr.',
            'name_suffix'    => 'Jr.',
        ]);

        $this->assertEquals('John', $name->first);
        $this->assertEquals('Mr. John Doe Jr.', $name->formatted);
        $this->assertEquals('Doe', $name->last);
        $this->assertEquals('Whatever', $name->middle);
        $this->assertEquals('Mr.', $name->prefix);
        $this->assertEquals('Jr.', $name->suffix);
    }
}
