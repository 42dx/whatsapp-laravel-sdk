<?php

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\Changes\{ContactsEntity, MessagesEntity};
use The42dx\Whatsapp\Entities\Message\MessageEntity;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class MessagesEntityTest extends UnitTestCase {
    public function test_itShouldBeAnEntityInstanceObject() {
        $changes = new MessagesEntity([]);

        $this->assertIsObject($changes);
        $this->assertTrue($changes instanceof Entity);
    }

    public function test__construct__it_should_create_object_with_correct_attributes() {
        $messageData = $this->getJsonFixture('Api/Components/change-messages');
        $message     = new MessagesEntity($messageData);

        $this->assertIsObject($message);

        $this->assertEquals('123123123', $message->waId);

        $this->assertEquals('5541987987987', $message->phone);

        $this->assertInstanceOf(Collection::class, $message->contacts);
        $this->assertEquals(1, $message->contacts->count());
        $this->assertInstanceOf(ContactsEntity::class, $message->contacts->first());

        $this->assertInstanceOf(Collection::class, $message->messages);
        $this->assertEquals(1, $message->messages->count());
        $this->assertInstanceOf(MessageEntity::class, $message->messages->first());
    }

    public function test__setAttributes__it_should_update_attributes() {
        $expectedWaId = '999999999';
        $expectedNumber = '00000000';
        $message = new MessagesEntity([]);

        $this->assertNull($message->waId);
        $this->assertNull($message->phone);
        $this->assertNull($message->contacts);
        $this->assertNull($message->messages);

        $message->setAttributes([
            "metadata" => [
                "display_phone_number" => $expectedNumber,
                "phone_number_id" => $expectedWaId
            ],
            "contacts" => [
                [
                    "profile" => [
                        "name" => "Some Name And Surname"
                    ],
                    "wa_id" => "1234567890"
                ]
            ],
            "messages" => [
                [
                    "from" => "66666666666",
                    "id" => "ABGGFlA5Fpa",
                    "timestamp" => "1504902988",
                    "type" => "text",
                    "text" => [
                        "body" => "Some message"
                    ]
                ]
            ]
        ]);

        $this->assertEquals($expectedWaId, $message->waId);
        $this->assertEquals($expectedNumber, $message->phone);
        $this->assertInstanceOf(ContactsEntity::class, $message->contacts->first());
        $this->assertInstanceOf(MessageEntity::class, $message->messages->first());
        $this->assertNotNull($message->messages->first()->id);
    }

    public function test__toArray__it_should_convert_to_array_correctly() {
        $messageData = $this->getJsonFixture('Api/Components/change-messages');
        $message     = new MessagesEntity($messageData);
        $array     = $message->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('waId', $array);
        $this->assertArrayHasKey('phone', $array);
        $this->assertArrayHasKey('contacts', $array);
        $this->assertArrayHasKey('messages', $array);
    }

    public function test__toJson__it_should_convert_to_json_correctly() {
        $messageData = $this->getJsonFixture('Api/Components/change-messages');
        $message     = new MessagesEntity($messageData);
        $json        = $message->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('waId', $json);
        $this->assertStringContainsString('phone', $json);
        $this->assertStringContainsString('contacts', $json);
        $this->assertStringContainsString('messages', $json);
    }
}
