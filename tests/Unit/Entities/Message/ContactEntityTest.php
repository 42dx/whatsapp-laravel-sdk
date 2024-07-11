<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Entities\Message\AddressEntity;
use The42dx\Whatsapp\Entities\Message\ContactEntity;
use The42dx\Whatsapp\Entities\Message\EmailEntity;
use The42dx\Whatsapp\Entities\Message\NameEntity;
use The42dx\Whatsapp\Entities\Message\OrgEntity;
use The42dx\Whatsapp\Entities\Message\PhoneEntity;
use The42dx\Whatsapp\Entities\Message\UrlEntity;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class ContactEntityTest extends UnitTestCase {
    public function test__construct__it_should_be_an_entity_instance_object() {
        $contact = new ContactEntity([]);

        $this->assertIsObject($contact);
        $this->assertInstanceOf(Entity::class, $contact);
    }

    public function test__construct__it_should_create_object_with_correct_attributes() {
        $expectedBirthday = '2012-08-18';
        $contactData      = $this->getJsonFixture('Api/Components/contact');
        $contact          = new ContactEntity($contactData);

        $this->assertIsObject($contact);

        $this->assertInstanceOf(AddressEntity::class, $contact->addresses);
        $this->assertEquals($expectedBirthday, $contact->birthday);
        $this->assertInstanceOf(Collection::class, $contact->emails);
        $this->assertInstanceOf(EmailEntity::class, $contact->emails->first());
        $this->assertInstanceOf(NameEntity::class, $contact->name);
        $this->assertInstanceOf(OrgEntity::class, $contact->org);
        $this->assertInstanceOf(Collection::class, $contact->phones);
        $this->assertInstanceOf(PhoneEntity::class, $contact->phones->first());
        $this->assertInstanceOf(Collection::class, $contact->urls);
        $this->assertInstanceOf(UrlEntity::class, $contact->urls->first());
    }

    public function test__setAttributes__it_should_update_attributes() {
        $expectedBirthday = '2012-08-18';
        $contact = new ContactEntity([]);

        $this->assertIsObject($contact);

        $this->assertNull($contact->addresses);
        $this->assertNull($contact->birthday);
        $this->assertNull($contact->emails);
        $this->assertNull($contact->name);
        $this->assertNull($contact->org);
        $this->assertNull($contact->phones);
        $this->assertNull($contact->urls);

        $contact->setAttributes([
            'addresses' => [
                []
            ],
            'birthday' => $expectedBirthday,
            'emails' => [
                []
            ],
            'name' => [],
            'org' => [],
            'phones' => [
                []
            ],
            'urls' => [
                []
            ]
        ]);

        $this->assertInstanceOf(AddressEntity::class, $contact->addresses);
        $this->assertEquals($expectedBirthday, $contact->birthday);
        $this->assertInstanceOf(Collection::class, $contact->emails);
        $this->assertInstanceOf(EmailEntity::class, $contact->emails->first());
        $this->assertInstanceOf(NameEntity::class, $contact->name);
        $this->assertInstanceOf(OrgEntity::class, $contact->org);
        $this->assertInstanceOf(Collection::class, $contact->phones);
        $this->assertInstanceOf(PhoneEntity::class, $contact->phones->first());
        $this->assertInstanceOf(Collection::class, $contact->urls);
        $this->assertInstanceOf(UrlEntity::class, $contact->urls->first());
    }

    public function test__toArray__it_should_convert_to_array_correctly() {
        $contactData      = $this->getJsonFixture('Api/Components/contact');
        $contact          = new ContactEntity($contactData);
        $array            = $contact->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('addresses', $array);
        $this->assertArrayHasKey('birthday', $array);
        $this->assertArrayHasKey('emails', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('org', $array);
        $this->assertArrayHasKey('phones', $array);
        $this->assertArrayHasKey('urls', $array);
    }

    public function test__toJson__it_should_convert_to_json_correctly() {
        $contactData      = $this->getJsonFixture('Api/Components/contact');
        $contact          = new ContactEntity($contactData);
        $json             = $contact->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('addresses', $json);
        $this->assertStringContainsString('birthday', $json);
        $this->assertStringContainsString('emails', $json);
        $this->assertStringContainsString('name', $json);
        $this->assertStringContainsString('org', $json);
        $this->assertStringContainsString('phones', $json);
        $this->assertStringContainsString('urls', $json);
    }
}
