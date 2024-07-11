<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Entities\Message\VideoEntity;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class VideoEntityTest extends UnitTestCase {
    public function test__construct_it_should_be_an_entity_instance_object() {
        $video = new VideoEntity([]);

        $this->assertIsObject($video);
        $this->assertInstanceOf(Entity::class, $video);
    }

    public function test__construct_it_should_create_object_with_correct_attributes() {
        $videoData = $this->getJsonFixture('Api/Components/media-video');
        $video     = new VideoEntity($videoData);

        $this->assertIsObject($video);

        $this->assertEquals('909554674312776', $video->id);
        $this->assertEquals('video/mp4', $video->mimeType);
        $this->assertEquals('0/OetMGZSTzoTIqs+siTFrSwqNE8k1cm9b9xkZWQbVQ=', $video->hash);
        $this->assertEquals('This is a caption for the video', $video->caption);

        $this->assertNull($video->link); // adjust when media link retrieve is implemented
        $this->assertNull($video->fileSize); // adjust when media link retrieve is implemented
    }

    public function test__setAttributes_it_should_update_attributes() {
        $video = new VideoEntity([]);

        $this->assertIsObject($video);

        $this->assertNull($video->id);
        $this->assertNull($video->mimeType);
        $this->assertNull($video->hash);
        $this->assertNull($video->link);
        $this->assertNull($video->fileSize);

        $video->setAttributes([
            'caption' => 'This is a caption for the video',
            'id' => '909554674312776',
            'mime_type' => 'video/mp4',
            'sha256' => '0/OetMGZSTzoTIqs+siTFrSwqNE8k1cm9b9xkZWQbVQ=',
        ]);

        $this->assertEquals('909554674312776', $video->id);
        $this->assertEquals('video/mp4', $video->mimeType);
        $this->assertEquals('0/OetMGZSTzoTIqs+siTFrSwqNE8k1cm9b9xkZWQbVQ=', $video->hash);
        $this->assertEquals('This is a caption for the video', $video->caption);

        $this->assertNull($video->link); // adjust when media link retrieve is implemented
        $this->assertNull($video->fileSize); // adjust when media link retrieve is implemented
    }

    public function test__toArray_it_should_convert_to_array_correctly() {
        $videoData = $this->getJsonFixture('Api/Components/media-video');
        $video     = new VideoEntity($videoData);
        $array     = $video->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('mimeType', $array);
        $this->assertArrayHasKey('hash', $array);
        $this->assertArrayHasKey('caption', $array);
        $this->assertArrayHasKey('link', $array);
        $this->assertArrayHasKey('fileSize', $array);
    }

    public function test__toJson_it_should_convert_to_json_correctly() {
        $videoData = $this->getJsonFixture('Api/Components/media-video');
        $video     = new VideoEntity($videoData);
        $json = $video->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('id', $json);
        $this->assertStringContainsString('mimeType', $json);
        $this->assertStringContainsString('hash', $json);
        $this->assertStringContainsString('caption', $json);
        $this->assertStringContainsString('link', $json);
        $this->assertStringContainsString('fileSize', $json);
    }
}
