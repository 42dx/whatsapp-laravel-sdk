<?php

namespace The42dx\Whatsapp\Tests\Unit\Factories;

use PHPUnit\Framework\Attributes\DataProvider;
use The42dx\Whatsapp\Enums\MessageComponent;
use The42dx\Whatsapp\Factories\WhatsappApiMessage;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class WhatsappApiMessageTest extends UnitTestCase {
    private $to = '11111111111';

    public static function templatesDataset(): array {
        return [
            'simple' => ['some_template', 'pt_BR'],
            'with 1 comp, 1 named param' => ['some_template', 'en_US', [['type' => MessageComponent::HEADER, 'params' => [['name' => 'some_name', 'text' => 'Whatever']]]]],
            'with 2 comp, multiple named params' => ['some_template', 'en_US', [
                ['type' => MessageComponent::HEADER, 'params' => [['name' => 'some_name', 'text' => 'Whatever']]],
                ['type' => MessageComponent::BODY, 'params' => [['name' => 'another_name', 'text' => 'pa'], ['name' => 'one_more_name', 'text' => 'pum']]],
            ]],
            'with 2 comp, multiple positional params' => ['some_template', 'en_US', [
                ['type' => MessageComponent::HEADER, 'params' => [['text' => 'Whatever']]],
                ['type' => MessageComponent::BODY, 'params' => [['text' => 'pa'], ['text' => 'pum']]],
            ]],
            'with indexed and subtyped param' => ['some_template', 'en_US', [
                ['type' => MessageComponent::BUTTON, 'subType' => MessageComponent::COPY_CODE, 'index' => 2, 'params' => [
                    ['type' => MessageComponent::COUPON_CODE, 'couponCode' => 'TEST123'],
                ]],
            ]],
        ];
    }

    public function test__construct__it_should_create_whatsapp_api_message(): void {
        $wppMsgApi = new WhatsappApiMessage;

        $this->assertInstanceOf(WhatsappApiMessage::class, $wppMsgApi);
    }

    public function test__get__it_should_return_the_value_of_private_attributes(): void {
        $wppMsgApi = new WhatsappApiMessage($this->to);

        $this->assertEquals($this->to, $wppMsgApi->to);
    }

    public function test__isset__it_should_check_if_provided_attribute_is_currently_set(): void {
        $wppMsgApi = WhatsappApiMessage::make($this->to);

        $this->assertTrue($wppMsgApi->__isset('to'));
        $this->assertFalse($wppMsgApi->__isset('whatever'));
    }

    public function test__make__it_should_create_whatsapp_api_message_with_static_method(): void {
        $wppMsgApi = WhatsappApiMessage::make($this->to);

        $this->assertInstanceOf(WhatsappApiMessage::class, $wppMsgApi);
    }

    public function test__compose__it_should_create_whatsapp_api_message_with_static_method(): void {
        $wppMsgApi = WhatsappApiMessage::compose(to: $this->to);

        $this->assertInstanceOf(WhatsappApiMessage::class, $wppMsgApi);
    }

    public function test__to_array__it_should_return_the_array_representation_of_the_object(): void {
        $text = 'some text';
        $msgId = '123456';
        $wppMsgApi = WhatsappApiMessage::compose(to: $this->to)
            ->replyTo(msg: $msgId)
            ->with(text: $text);
        $array = $wppMsgApi->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('to', $array);
        $this->assertArrayHasKey('text', $array);
        $this->assertArrayHasKey('body', $array['text']);
        $this->assertEquals($text, $array['text']['body']);
        $this->assertArrayHasKey('context', $array);
        $this->assertArrayHasKey('message_id', $array['context']);
        $this->assertEquals($msgId, $array['context']['message_id']);
    }

    public function test__to_json__it_should_return_the_json_string_representation_of_the_object(): void {
        $text = 'some text';
        $msgId = '123456';
        $wppMsgApi = WhatsappApiMessage::compose(to: $this->to)
            ->replyTo(msg: $msgId)
            ->with(text: $text);
        $json = $wppMsgApi->toJson();

        $this->assertJson($json);
    }

    public function test__to__it_should_set_the_recipient_number(): void {
        $newNumber = '22222222222';
        $wppMsgApi = WhatsappApiMessage::compose(to: $this->to);

        $wppMsgApi->to($newNumber);

        $this->assertEquals($newNumber, $wppMsgApi->to);
    }

    public function test__reply_to__it_should_set_the_message_context(): void {
        $replyToMsgId = '123456';
        $wppMsgApi = WhatsappApiMessage::compose(to: $this->to)
            ->replyTo(msg: $replyToMsgId);

        $this->assertArrayHasKey('message_id', $wppMsgApi->context);
        $this->assertEquals($replyToMsgId, $wppMsgApi->context['message_id']);
    }

    public function test__with_text__it_should_set_the_message_text_body(): void {
        $text = 'some text';
        $wppMsgApi = WhatsappApiMessage::compose(to: $this->to)
            ->withText($text);

        $this->assertArrayHasKey('body', $wppMsgApi->text);
        $this->assertEquals($text, $wppMsgApi->text['body']);
    }

    public function test__with__it_should_set_the_message_text_body(): void {
        $text = 'some text';
        $wppMsgApi = WhatsappApiMessage::compose(to: $this->to)
            ->with($text);

        $this->assertArrayHasKey('body', $wppMsgApi->text);
        $this->assertEquals($text, $wppMsgApi->text['body']);
    }

    public function test__react_to__it_should_set_the_message_reaction(): void {
        $msgId = '1235436';
        $reaction = '👍';
        $wppMsgApi = WhatsappApiMessage::compose(to: $this->to)
            ->reactTo(msg: $msgId, with: $reaction);

        $this->assertArrayHasKey('emoji', $wppMsgApi->reaction);
        $this->assertEquals($reaction, $wppMsgApi->reaction['emoji']);
        $this->assertArrayHasKey('message_id', $wppMsgApi->reaction);
        $this->assertEquals($msgId, $wppMsgApi->reaction['message_id']);
    }

    #[DataProvider('templatesDataset')]
    public function test__using_template__it_should_set_the_message_template(string $name, string $lang, array $components = []): void {
        $wppMsgApi = WhatsappApiMessage::compose(to: $this->to)
            ->usingTemplate($name, $lang);

        $this->assertArrayHasKey('name', $wppMsgApi->template);
        $this->assertEquals($name, $wppMsgApi->template['name']);
        $this->assertArrayHasKey('language', $wppMsgApi->template);
        $this->assertArrayHasKey('code', $wppMsgApi->template['language']);
        $this->assertEquals($lang, $wppMsgApi->template['language']['code']);

        if (count($components)) {
            foreach ($components as $key => $component) {
                $wppMsgApi->withComponent($component['type'], $component['params'], $component['subType'] ?? null, $component['index'] ?? null);

                $this->assertEquals($component['type']->value, $wppMsgApi->template['components'][$key]['type']);
                $this->assertEquals(isset($component['subType']) ? $component['subType']->value : null, $wppMsgApi->template['components'][$key]['sub_type']);
                $this->assertEquals($component['index'] ?? null, $wppMsgApi->template['components'][$key]['index']);

                foreach ($component['params'] as $k => $param) {
                    $this->assertCount(count($component['params']), $wppMsgApi->template['components'][$key]['parameters']);
                    $this->assertEquals($param['text'] ?? null, $wppMsgApi->template['components'][$key]['parameters'][$k]['text'] ?? null);
                    $this->assertEquals($param['name'] ?? null, $wppMsgApi->template['components'][$key]['parameters'][$k]['parameter_name'] ?? null);
                    $this->assertEquals($param['subType'] ?? null, $wppMsgApi->template['components'][$key]['parameters'][$k]['sub_type'] ?? null);
                    $this->assertEquals($param['couponCode'] ?? null, $wppMsgApi->template['components'][$key]['parameters'][$k]['coupon_code'] ?? null);
                }
            }

            $this->assertArrayHasKey('components', $wppMsgApi->template);
            $this->assertCount(count($components), $wppMsgApi->template['components']);
        }
    }
}
