<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\Message\ContextEntity;
use The42dx\Whatsapp\Enums\ContextType;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class ContextEntityTest extends UnitTestCase {
    public function test_itShouldBeAnEntryInstanceObject() {
        $context = new ContextEntity([]);

        $this->assertIsObject($context);
        $this->assertInstanceOf(Entity::class, $context);
    }

    public function test_itShouldCreateObjectWithCorrectAttributes() {
        $contextData = $this->getJsonFixture('Api/Components/context');
        $context     = new ContextEntity($contextData);

        $this->assertIsObject($context);

        $this->assertEquals('8231jouiwfe9823jr', $context->id);
        $this->assertEquals('5541999999999', $context->from);
        $this->assertInstanceOf(ContextType::class, $context->type);
        $this->assertEquals(ContextType::STD, $context->type);
    }

    public function test_itShouldUpdateAttributes() {
        $context = new ContextEntity([]);

        $this->assertIsObject($context);

        $this->assertNull($context->id);
        $this->assertNull($context->from);
        $this->assertEquals(ContextType::STD, $context->type);

        $context->setAttributes([
            'forwarded' => false,
            'frequently_forwarded' => false,
            'from' => '5541999999999',
            'id' => '8231jouiwfe9823jr',
        ]);

        $this->assertEquals('8231jouiwfe9823jr', $context->id);
        $this->assertEquals('5541999999999', $context->from);
        $this->assertInstanceOf(ContextType::class, $context->type);
        $this->assertEquals(ContextType::STD, $context->type);
    }

    public function test_itShouldConvertToArrayCorrectly() {
        $contextData = $this->getJsonFixture('Api/Components/context');
        $context     = new ContextEntity($contextData);
        $array     = $context->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('from', $array);
        $this->assertArrayHasKey('type', $array);
    }

    public function test_itShouldConvertToJsonCorrectly() {
        $contextData = $this->getJsonFixture('Api/Components/context');
        $context     = new ContextEntity($contextData);
        $json        = $context->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('id', $json);
        $this->assertStringContainsString('from', $json);
        $this->assertStringContainsString('type', $json);
    }
}
