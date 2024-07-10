<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities\Message;

use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\Message\OrgEntity;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class OrgEntityTest extends UnitTestCase {
    public function test_itShouldBeAnEntryInstanceObject() {
        $org = new OrgEntity([]);

        $this->assertIsObject($org);
        $this->assertInstanceOf(Entity::class, $org);
    }

    public function test_itShouldCreateObjectWithCorrectAttributes() {
        $orgData = $this->getJsonFixture('Api/Components/org');
        $org     = new OrgEntity($orgData);

        $this->assertIsObject($org);

        $this->assertEquals('some company', $org->company);
        $this->assertEquals('some department', $org->department);
        $this->assertEquals('some title', $org->title);
    }

    public function test_itShouldUpdateAttributes() {
        $org = new OrgEntity([]);

        $this->assertIsObject($org);

        $this->assertNull($org->company);
        $this->assertNull($org->department);
        $this->assertNull($org->title);

        $org->setAttributes([
            'company'     => 'some company',
            'department'  => 'some department',
            'title'       => 'some title',
        ]);

        $this->assertEquals('some company', $org->company);
        $this->assertEquals('some department', $org->department);
        $this->assertEquals('some title', $org->title);
    }

    public function test_itShouldConvertToArrayCorrectly() {
        $orgData = $this->getJsonFixture('Api/Components/org');
        $org     = new OrgEntity($orgData);
        $array   = $org->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('company', $array);
        $this->assertArrayHasKey('department', $array);
        $this->assertArrayHasKey('title', $array);
    }

    public function test_itShouldConvertToJsonCorrectly() {
        $orgData = $this->getJsonFixture('Api/Components/org');
        $org     = new OrgEntity($orgData);
        $json    = $org->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('company', $json);
        $this->assertStringContainsString('department', $json);
        $this->assertStringContainsString('title', $json);
    }
}
