<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Entities\Message\DocumentEntity;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class DocumentEntityTest extends UnitTestCase {
    public function test_itShouldBeAnEntryInstanceObject() {
        $document = new DocumentEntity([]);

        $this->assertIsObject($document);
        $this->assertInstanceOf(Entity::class, $document);
    }

    public function test_itShouldCreateObjectWithCorrectAttributes() {
        $documentData = $this->getJsonFixture('Api/Components/media-document');
        $document     = new DocumentEntity($documentData);

        $this->assertIsObject($document);

        $this->assertEquals('1484749952126129', $document->id);
        $this->assertEquals('application/pdf', $document->mimeType);
        $this->assertEquals('37POPvAFzXugG0L6EG/a1z852cxUKAbmB7BvJzadMbw=', $document->hash);
        $this->assertEquals('This is a caption for the document', $document->caption);
        $this->assertEquals('Comprovante_09-07-2024_131947.pdf', $document->filename);

        $this->assertNull($document->link); // adjust when media link retrieve is implemented
        $this->assertNull($document->fileSize); // adjust when media link retrieve is implemented
    }

    public function test_itShouldUpdateAttributes() {
        $document = new DocumentEntity([]);

        $this->assertIsObject($document);

        $this->assertNull($document->id);
        $this->assertNull($document->mimeType);
        $this->assertNull($document->hash);
        $this->assertNull($document->link);
        $this->assertNull($document->fileSize);

        $document->setAttributes([
            'filename' => 'Comprovante_09-07-2024_131947.pdf',
            'id' => '1484749952126129',
            'caption' => 'This is a caption for the document',
            'mime_type' => 'application/pdf',
            'sha256' => '37POPvAFzXugG0L6EG/a1z852cxUKAbmB7BvJzadMbw=',
        ]);

        $this->assertEquals('1484749952126129', $document->id);
        $this->assertEquals('application/pdf', $document->mimeType);
        $this->assertEquals('37POPvAFzXugG0L6EG/a1z852cxUKAbmB7BvJzadMbw=', $document->hash);
        $this->assertEquals('This is a caption for the document', $document->caption);
        $this->assertEquals('Comprovante_09-07-2024_131947.pdf', $document->filename);

        $this->assertNull($document->link); // adjust when media link retrieve is implemented
        $this->assertNull($document->fileSize); // adjust when media link retrieve is implemented
    }

    public function test_itShouldConvertToArrayCorrectly() {
        $documentData = $this->getJsonFixture('Api/Components/media-document');
        $document     = new DocumentEntity($documentData);
        $array        = $document->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('mimeType', $array);
        $this->assertArrayHasKey('hash', $array);
        $this->assertArrayHasKey('caption', $array);
        $this->assertArrayHasKey('filename', $array);
        $this->assertArrayHasKey('link', $array);
        $this->assertArrayHasKey('fileSize', $array);
    }

    public function test_itShouldConvertToJsonCorrectly() {
        $documentData = $this->getJsonFixture('Api/Components/media-document');
        $document     = new DocumentEntity($documentData);
        $json         = $document->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('id', $json);
        $this->assertStringContainsString('mimeType', $json);
        $this->assertStringContainsString('hash', $json);
        $this->assertStringContainsString('caption', $json);
        $this->assertStringContainsString('filename', $json);
        $this->assertStringContainsString('link', $json);
        $this->assertStringContainsString('fileSize', $json);
    }
}
