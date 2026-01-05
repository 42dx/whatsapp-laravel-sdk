<?php

namespace The42dx\Whatsapp\Tests\Unit\Abstracts;

use InvalidArgumentException;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class Mock extends Entity {
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('unexistent', 'unexistent', $attributes);

        return $this;
    }
}

class EntityTest extends UnitTestCase {
    public function test__set_attributes__it_should_throw_error_if_provided_property_does_not_exist(): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Attribute \'unexistent\' does not exist on [The42dx\Whatsapp\Tests\Unit\Abstracts\Mock].');

        new Mock(['unexistent' => 'something']);
    }
}
