<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Entities\Message\AudioEntity;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class AudioEntityTest extends UnitTestCase {
    public function test__construct_it_should_be_an_entity_instance_object() {
        $audio = new AudioEntity([]);

        $this->assertIsObject($audio);
        $this->assertInstanceOf(Entity::class, $audio);
    }

    public function test__construct_it_should_create_object_with_correct_attributes() {
        $audioData = $this->getJsonFixture('Api/Components/media-audio');
        $audio     = new AudioEntity($audioData);

        $this->assertIsObject($audio);

        $this->assertEquals('804086435201373', $audio->id);
        $this->assertEquals('audio/ogg; codecs=opus', $audio->mimeType);
        $this->assertEquals('G4jMrsRgbCEXehttn0RvlfM3DwxlMohX4Z8l8XmlhA=', $audio->hash);

        $this->assertNull($audio->link); // adjust when media link retrieve is implemented
        $this->assertNull($audio->fileSize); // adjust when media link retrieve is implemented
    }

    public function test__setAttributes_it_should_update_attributes() {
        $audio = new AudioEntity([]);

        $this->assertIsObject($audio);

        $this->assertNull($audio->id);
        $this->assertNull($audio->mimeType);
        $this->assertNull($audio->hash);
        $this->assertNull($audio->link);
        $this->assertNull($audio->fileSize);

        $audio->setAttributes([
            'id'        => '804086435201373',
            'mime_type' => 'audio/ogg; codecs=opus',
            'sha256'    => 'G4jMrsRgbCEXehttn0RvlfM3DwxlMohX4Z8l8XmlhA=',
        ]);

        $this->assertEquals('804086435201373', $audio->id);
        $this->assertEquals('audio/ogg; codecs=opus', $audio->mimeType);
        $this->assertEquals('G4jMrsRgbCEXehttn0RvlfM3DwxlMohX4Z8l8XmlhA=', $audio->hash);

        $this->assertNull($audio->link); // adjust when media link retrieve is implemented
        $this->assertNull($audio->fileSize); // adjust when media link retrieve is implemented
    }

    public function test__toArray_it_should_convert_to_array_correctly() {
        $audioData = $this->getJsonFixture('Api/Components/media-audio');
        $audio     = new AudioEntity($audioData);
        $array     = $audio->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('mimeType', $array);
        $this->assertArrayHasKey('hash', $array);
        $this->assertArrayHasKey('link', $array);
        $this->assertArrayHasKey('fileSize', $array);
    }

    public function test__toJson_it_should_convert_to_json_correctly() {
        $audioData = $this->getJsonFixture('Api/Components/media-audio');
        $audio     = new AudioEntity($audioData);
        $json      = $audio->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('id', $json);
        $this->assertStringContainsString('mimeType', $json);
        $this->assertStringContainsString('hash', $json);
        $this->assertStringContainsString('link', $json);
        $this->assertStringContainsString('fileSize', $json);
    }
}
