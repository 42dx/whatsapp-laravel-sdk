<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Entities\Message\EmailEntity;
use The42dx\Whatsapp\Enums\ContactPropType;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class EmailEntityTest extends UnitTestCase {
    public function test_itShouldBeAnEntryInstanceObject() {
        $email = new EmailEntity([]);

        $this->assertIsObject($email);
        $this->assertInstanceOf(Entity::class, $email);
    }

    public function test_itShouldCreateObjectWithCorrectAttributes() {
        $expectedEmail = 'kfish@fb.com';
        $emailData     = $this->getJsonFixture('Api/Components/email');
        $email         = new EmailEntity($emailData);

        $this->assertIsObject($email);

        $this->assertEquals($expectedEmail, $email->email);
        $this->assertEquals(ContactPropType::WORK, $email->type);
    }

    public function test_itShouldUpdateAttributes() {
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
}
