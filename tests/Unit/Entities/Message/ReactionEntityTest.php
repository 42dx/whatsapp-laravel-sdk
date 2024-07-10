<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\Message\ReactionEntity;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class ReactionEntityTest extends UnitTestCase {
    public function test_itShouldBeAnEntryInstanceObject() {
        $reaction = new ReactionEntity([]);

        $this->assertIsObject($reaction);
        $this->assertInstanceOf(Entity::class, $reaction);
    }

    public function test_itShouldCreateObjectWithCorrectAttributes() {
        $reactionData = $this->getJsonFixture('Api/Components/reaction');
        $reaction     = new ReactionEntity($reactionData);

        $this->assertIsObject($reaction);

        $this->assertEquals('😂', $reaction->emoji);
        $this->assertEquals('123456', $reaction->reactedId);
    }

    public function test_itShouldUpdateAttributes() {
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

    public function test_itShouldConvertToArrayCorrectly() {
        $reactionData = $this->getJsonFixture('Api/Components/reaction');
        $reaction     = new ReactionEntity($reactionData);
        $array        = $reaction->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('emoji', $array);
        $this->assertArrayHasKey('reactedId', $array);
    }

    public function test_itShouldConvertToJsonCorrectly() {
        $reactionData = $this->getJsonFixture('Api/Components/reaction');
        $reaction     = new ReactionEntity($reactionData);
        $json         = $reaction->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('emoji', $json);
        $this->assertStringContainsString('reactedId', $json);
    }
}
