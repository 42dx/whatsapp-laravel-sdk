<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\Message\ContextEntity;
use The42dx\Whatsapp\Enums\ContextType;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class ContextEntityTest extends UnitTestCase {
    public function test__construct__it_should_be_an_entity_instance_object(): void {
        $context = new ContextEntity([]);

        $this->assertIsObject($context);
        $this->assertInstanceOf(Entity::class, $context);
    }

    public function test__construct__it_should_create_object_with_correct_attributes(): void {
        $context = new ContextEntity([]);

        $this->assertIsObject($context);

        $this->assertInstanceOf(ContextType::class, $context->type);
        $this->assertEquals(ContextType::STD, $context->type);
    }

    public function test__set_attributes__it_should_update_attributes(): void {
        $context = new ContextEntity;

        $this->assertIsObject($context);

        $this->assertNull($context->id);
        $this->assertNull($context->from);

        $context->setAttributes([
            'forwarded' => false,
            'frequently_forwarded' => false,
            'from' => '5541999999999',
            'id' => '8231jouiwfe9823jr',
        ]);

        $this->assertEquals('8231jouiwfe9823jr', $context->id);
        $this->assertEquals('5541999999999', $context->from);
        $this->assertInstanceOf(ContextType::class, $context->type);
    }

    public function test__to_array__it_should_convert_to_array_correctly(): void {
        $contextData = self::getJsonFixture('Api/Components/context');
        $context = new ContextEntity($contextData);
        $array = $context->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('from', $array);
        $this->assertArrayHasKey('type', $array);
    }

    public function test__to_json__it_should_convert_to_json_correctly(): void {
        $contextData = self::getJsonFixture('Api/Components/context');
        $context = new ContextEntity($contextData);
        $json = $context->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('id', $json);
        $this->assertStringContainsString('from', $json);
        $this->assertStringContainsString('type', $json);
    }
}
