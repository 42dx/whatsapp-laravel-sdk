<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\Message\ReactionEntity;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class ReactionEntityTest extends UnitTestCase {
    public function test__construct__it_should_be_an_entity_instance_object() {
        $reaction = new ReactionEntity([]);

        $this->assertIsObject($reaction);
        $this->assertInstanceOf(Entity::class, $reaction);
    }

    public function test__construct__it_should_create_object_with_correct_attributes() {
        $reactionData = $this->getJsonFixture('Api/Components/reaction');
        $reaction     = new ReactionEntity($reactionData);

        $this->assertIsObject($reaction);

        $this->assertEquals('😂', $reaction->emoji);
        $this->assertEquals('123456', $reaction->reactedId);
    }

    public function test__setAttributes__it_should_update_attributes() {
        $reaction = new ReactionEntity([]);

        $this->assertIsObject($reaction);

        $this->assertNull($reaction->emoji);
        $this->assertNull($reaction->reactedId);

        $reaction->setAttributes([
            'emoji'      => '😂',
            'message_id' => '123456',
        ]);

        $this->assertEquals('😂', $reaction->emoji);
        $this->assertEquals('123456', $reaction->reactedId);
    }

    public function test__toArray__it_should_convert_to_array_correctly() {
        $reactionData = $this->getJsonFixture('Api/Components/reaction');
        $reaction     = new ReactionEntity($reactionData);
        $array        = $reaction->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('emoji', $array);
        $this->assertArrayHasKey('reactedId', $array);
    }

    public function test__toJson__it_should_convert_to_json_correctly() {
        $reactionData = $this->getJsonFixture('Api/Components/reaction');
        $reaction     = new ReactionEntity($reactionData);
        $json         = $reaction->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('emoji', $json);
        $this->assertStringContainsString('reactedId', $json);
    }
}
