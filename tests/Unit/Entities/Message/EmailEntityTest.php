<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Entities\Message\EmailEntity;
use The42dx\Whatsapp\Enums\ContactPropType;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class EmailEntityTest extends UnitTestCase {
    public function test__construct_it_should_be_an_entity_instance_object() {
        $email = new EmailEntity([]);

        $this->assertIsObject($email);
        $this->assertInstanceOf(Entity::class, $email);
    }

    public function test__construct_it_should_create_object_with_correct_attributes() {
        $expectedEmail = 'kfish@fb.com';
        $emailData     = $this->getJsonFixture('Api/Components/email');
        $email         = new EmailEntity($emailData);

        $this->assertIsObject($email);

        $this->assertEquals($expectedEmail, $email->email);
        $this->assertEquals(ContactPropType::WORK, $email->type);
    }

    public function test__setAttributes_it_should_update_attributes() {
        $expectedEmail = 'kfish@fb.com';
        $email         = new EmailEntity([]);

        $this->assertIsObject($email);

        $this->assertNull($email->email);
        $this->assertNull($email->type);

        $email->setAttributes([
            'email' => $expectedEmail,
            'type'  => ContactPropType::WORK->value,
        ]);

        $this->assertEquals($expectedEmail, $email->email);
        $this->assertEquals(ContactPropType::WORK, $email->type);
    }

    public function test__toArray_it_should_convert_to_array_correctly() {
        $emailData = $this->getJsonFixture('Api/Components/email');
        $email     = new EmailEntity($emailData);
        $array     = $email->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('email', $array);
        $this->assertArrayHasKey('type', $array);
    }

    public function test__toJson_it_should_convert_to_json_correctly() {
        $emailData = $this->getJsonFixture('Api/Components/email');
        $email     = new EmailEntity($emailData);
        $json      = $email->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('email', $json);
        $this->assertStringContainsString('type', $json);
    }
}
