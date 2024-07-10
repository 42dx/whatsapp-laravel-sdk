<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Entities\Message\AudioEntity;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class AudioEntityTest extends UnitTestCase {
    public function test_itShouldBeAnEntryInstanceObject() {
        $audio = new AudioEntity([]);

        $this->assertIsObject($audio);
        $this->assertInstanceOf(Entity::class, $audio);
    }

    public function test_itShouldCreateObjectWithCorrectAttributes() {
        $audioData = $this->getJsonFixture('Api/Components/media-audio');
        $audio     = new AudioEntity($audioData);

        $this->assertIsObject($audio);

        $this->assertEquals('804086435201373', $audio->id);
        $this->assertEquals('audio/ogg; codecs=opus', $audio->mimeType);
        $this->assertEquals('G4jMrsRgbCEXehttn0RvlfM3DwxlMohX4Z8l8XmlhA=', $audio->hash);

        $this->assertNull($audio->link); // adjust when media link retrieve is implemented
        $this->assertNull($audio->fileSize); // adjust when media link retrieve is implemented
    }

    public function test_itShouldUpdateAttributes() {
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
}
