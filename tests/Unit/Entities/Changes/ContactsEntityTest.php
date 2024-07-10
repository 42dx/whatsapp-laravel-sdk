<?php

use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\Changes\ContactsEntity;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class ContactsEntityTest extends UnitTestCase {
    public function test_itShouldBeAnEntityInstanceObject() {
        $changes = new ContactsEntity([]);

        $this->assertIsObject($changes);
        $this->assertTrue($changes instanceof Entity);
    }

    public function test_itShouldCreateObjectWithCorrectAttributes() {
        $contactData = $this->getJsonFixture('Api/Components/change-contact');
        $contact     = new ContactsEntity($contactData);

        $this->assertIsObject($contact);

        $this->assertNotNull($contact->name);
        $this->assertEquals('Some Name And Surname', $contact->name);

        $this->assertNotNull($contact->waId);
        $this->assertEquals('123123123123', $contact->waId);
    }

    public function test_itShouldUpdateAttributes() {
        $expectedName = 'Some Interesting Name';
        $expectedWaId = '098876654';
        $contact = new ContactsEntity([]);

        $this->assertNull($contact->name);
        $this->assertNull($contact->waId);

        $contact->setAttributes([
            'profile' => [
                'name' => $expectedName
            ],
            'wa_id'   => $expectedWaId,
        ]);

        $this->assertNotNull($contact->name);
        $this->assertEquals($expectedName, $contact->name);

        $this->assertNotNull($contact->waId);
        $this->assertEquals($expectedWaId, $contact->waId);
    }

    public function test_itShouldConvertToArrayCorrectly() {
        $contactData = $this->getJsonFixture('Api/Components/change-contact');
        $contact     = new ContactsEntity($contactData);
        $array     = $contact->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('waId', $array);
    }

    public function test_itShouldConvertToJsonCorrectly() {
        $contactData = $this->getJsonFixture('Api/Components/change-contact');
        $contact     = new ContactsEntity($contactData);
        $json        = $contact->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('name', $json);
        $this->assertStringContainsString('waId', $json);
    }
}
