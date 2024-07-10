<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Entities\Message\ImageEntity;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class ImageEntityTest extends UnitTestCase {
    public function test_itShouldBeAnEntryInstanceObject() {
        $image = new ImageEntity([]);

        $this->assertIsObject($image);
        $this->assertInstanceOf(Entity::class, $image);
    }

    public function test_itShouldCreateObjectWithCorrectAttributes() {
        $imageData = $this->getJsonFixture('Api/Components/media-image');
        $image     = new ImageEntity($imageData);

        $this->assertIsObject($image);

        $this->assertEquals('995642658908843', $image->id);
        $this->assertEquals('image/jpeg', $image->mimeType);
        $this->assertEquals('Z9iQdUliWStJraYMMMYWY2aJTihGfixCU6DDQJSLi14=', $image->hash);
        $this->assertEquals('This is a caption for the image.', $image->caption);

        $this->assertNull($image->link); // adjust when media link retrieve is implemented
        $this->assertNull($image->fileSize); // adjust when media link retrieve is implemented
    }

    public function test_itShouldUpdateAttributes() {
        $image = new ImageEntity([]);

        $this->assertIsObject($image);

        $this->assertNull($image->id);
        $this->assertNull($image->mimeType);
        $this->assertNull($image->hash);
        $this->assertNull($image->link);
        $this->assertNull($image->fileSize);

        $image->setAttributes([
            'id'        => '995642658908843',
            'mime_type' => 'image/jpeg',
            'sha256'    => 'Z9iQdUliWStJraYMMMYWY2aJTihGfixCU6DDQJSLi14=',
            'caption'   => 'This is a caption for the image.'
        ]);

        $this->assertEquals('995642658908843', $image->id);
        $this->assertEquals('image/jpeg', $image->mimeType);
        $this->assertEquals('Z9iQdUliWStJraYMMMYWY2aJTihGfixCU6DDQJSLi14=', $image->hash);
        $this->assertEquals('This is a caption for the image.', $image->caption);

        $this->assertNull($image->link); // adjust when media link retrieve is implemented
        $this->assertNull($image->fileSize); // adjust when media link retrieve is implemented
    }

    public function test_itShouldConvertToArrayCorrectly() {
        $imageData = $this->getJsonFixture('Api/Components/media-image');
        $image     = new ImageEntity($imageData);
        $array     = $image->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('mimeType', $array);
        $this->assertArrayHasKey('hash', $array);
        $this->assertArrayHasKey('caption', $array);
    }

    public function test_itShouldConvertToJsonCorrectly() {
        $imageData = $this->getJsonFixture('Api/Components/media-image');
        $image     = new ImageEntity($imageData);
        $json      = $image->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('id', $json);
        $this->assertStringContainsString('mimeType', $json);
        $this->assertStringContainsString('hash', $json);
        $this->assertStringContainsString('caption', $json);
    }
}
