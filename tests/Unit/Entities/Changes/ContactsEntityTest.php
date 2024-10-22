<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Changes;

use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\Changes\ContactsEntity;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class ContactsEntityTest extends UnitTestCase {
    public function test_itShouldBeAnEntityInstanceObject() {
        $changes = new ContactsEntity([]);

        $this->assertIsObject($changes);
        $this->assertTrue($changes instanceof Entity);
    }

    public function test__construct__it_should_create_object_with_correct_attributes() {
        $contactData = $this->getJsonFixture('Api/Components/change-contact');
        $contact     = new ContactsEntity($contactData);

        $this->assertIsObject($contact);

        $this->assertNotNull($contact->name);
        $this->assertEquals('Some Name And Surname', $contact->name);

        $this->assertNotNull($contact->waId);
        $this->assertEquals('123123123123', $contact->waId);
    }

    public function test__setAttributes__it_should_update_attributes() {
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

    public function test__toArray__it_should_convert_to_array_correctly() {
        $contactData = $this->getJsonFixture('Api/Components/change-contact');
        $contact     = new ContactsEntity($contactData);
        $array     = $contact->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('waId', $array);
    }

    public function test__toJson__it_should_convert_to_json_correctly() {
        $contactData = $this->getJsonFixture('Api/Components/change-contact');
        $contact     = new ContactsEntity($contactData);
        $json        = $contact->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('name', $json);
        $this->assertStringContainsString('waId', $json);
    }
}
