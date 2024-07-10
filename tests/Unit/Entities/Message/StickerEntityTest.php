<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Entities\Message\StickerEntity;
use The42dx\Whatsapp\Enums\StickerType;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class StickerEntityTest extends UnitTestCase {
    public function test_itShouldBeAnEntryInstanceObject() {
        $sticker = new StickerEntity([]);

        $this->assertIsObject($sticker);
        $this->assertInstanceOf(Entity::class, $sticker);
    }

    public function test_itShouldCreateObjectWithCorrectAttributes() {
        $stickerData = $this->getJsonFixture('Api/Components/media-sticker');
        $sticker     = new StickerEntity($stickerData);

        $this->assertIsObject($sticker);

        $this->assertEquals('1007500390542147', $sticker->id);
        $this->assertEquals('image/webp', $sticker->mimeType);
        $this->assertEquals('RmzgRmJYltw3YcAMaZrektNCdwns5j21cWItBZdoMtM=', $sticker->hash);
        $this->assertInstanceOf(StickerType::class, $sticker->type);
        $this->assertEquals(StickerType::STATIC, $sticker->type);

        $this->assertNull($sticker->link); // adjust when media link retrieve is implemented
        $this->assertNull($sticker->fileSize); // adjust when media link retrieve is implemented
    }

    public function test_itShouldUpdateAttributes() {
        $sticker = new StickerEntity([]);

        $this->assertIsObject($sticker);

        $this->assertNull($sticker->id);
        $this->assertNull($sticker->mimeType);
        $this->assertNull($sticker->hash);
        $this->assertNull($sticker->link);
        $this->assertNull($sticker->fileSize);

        $sticker->setAttributes([
            'mime_type' => 'image/webp',
            'sha256' => 'RmzgRmJYltw3YcAMaZrektNCdwns5j21cWItBZdoMtM=',
            'id' => '1007500390542147',
            'animated' => false,
        ]);

        $this->assertEquals('1007500390542147', $sticker->id);
        $this->assertEquals('image/webp', $sticker->mimeType);
        $this->assertEquals('RmzgRmJYltw3YcAMaZrektNCdwns5j21cWItBZdoMtM=', $sticker->hash);
        $this->assertInstanceOf(StickerType::class, $sticker->type);
        $this->assertEquals(StickerType::STATIC, $sticker->type);

        $this->assertNull($sticker->link); // adjust when media link retrieve is implemented
        $this->assertNull($sticker->fileSize); // adjust when media link retrieve is implemented
    }

    public function test_itShouldConvertToArrayCorrectly() {
        $stickerData = $this->getJsonFixture('Api/Components/media-sticker');
        $sticker     = new StickerEntity($stickerData);
        $array     = $sticker->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('mimeType', $array);
        $this->assertArrayHasKey('hash', $array);
        $this->assertArrayHasKey('type', $array);
    }

    public function test_itShouldConvertToJsonCorrectly() {
        $stickerData = $this->getJsonFixture('Api/Components/media-sticker');
        $sticker     = new StickerEntity($stickerData);
        $json        = $sticker->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('id', $json);
        $this->assertStringContainsString('mimeType', $json);
        $this->assertStringContainsString('hash', $json);
        $this->assertStringContainsString('type', $json);
    }
}
