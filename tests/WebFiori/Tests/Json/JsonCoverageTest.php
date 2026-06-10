<?php

namespace WebFiori\Tests\Json;

use PHPUnit\Framework\TestCase;
use WebFiori\Json\Json;
use WebFiori\Json\JsonDeserializer;
use WebFiori\Json\JsonException;
use WebFiori\Json\JsonType;

class WithTypedSetter {
    private ?SimpleUser $user = null;
    public function setUser(SimpleUser $user): void { $this->user = $user; }
    public function getUser(): ?SimpleUser { return $this->user; }
}

class WithGetterRequiringParam {
    public function getName(): string { return 'test'; }
    public function getFiltered(string $filter): string { return $filter; }
}

class WithJsonTypeNonArray {
    public function __construct(
        #[JsonType(SimpleUser::class)]
        private $data
    ) {}
    public function getData() { return $this->data; }
}

class WithPropertyJsonType {
    #[JsonType(SimpleUser::class)]
    private $manager;
    private string $team;

    public function __construct(
        private string $name,
        $manager,
        string $team = 'default'
    ) {
        $this->manager = $manager;
        $this->team = $team;
    }
    public function getName(): string { return $this->name; }
    public function getManager() { return $this->manager; }
    public function getTeam(): string { return $this->team; }
}

class WithSetterJsonType {
    private array $items = [];
    private ?SimpleUser $lead = null;

    public function setItems(#[JsonType(LineItem::class, isArray: true)] array $items): void {
        $this->items = $items;
    }
    public function setLead(SimpleUser $lead): void { $this->lead = $lead; }
    public function getItems(): array { return $this->items; }
    public function getLead(): ?SimpleUser { return $this->lead; }
}

class JsonCoverageTest extends TestCase {

    public function testSetterWithTypedParam() {
        $obj = Json::decodeAs('{"user":{"username":"ib","email":"x@y.com"}}', WithTypedSetter::class);
        $this->assertInstanceOf(SimpleUser::class, $obj->getUser());
        $this->assertEquals('ib', $obj->getUser()->getUsername());
    }

    public function testJsonTypeAttributeNonArray() {
        $obj = Json::decodeAs('{"data":{"username":"test","email":"t@t.com"}}', WithJsonTypeNonArray::class);
        $this->assertInstanceOf(SimpleUser::class, $obj->getData());
        $this->assertEquals('test', $obj->getData()->getUsername());
    }

    public function testJsonTypeWithNonArrayValue() {
        $json = Json::decode('{"items":"not-an-array","status":"ok"}');
        $obj = JsonDeserializer::deserialize($json, WithJsonTypeProp::class);
        $this->assertEquals([], $obj->items);
    }

    public function testGetterWithRequiredParamSkipped() {
        $json = new Json();
        $obj = new WithGetterRequiringParam();
        $json->addObject('item', $obj);
        $str = $json->toJSONString();
        $this->assertStringContainsString('"Name":"test"', $str);
        $this->assertStringNotContainsString('Filtered', $str);
    }

    public function testSetTypeMapScalarNotConverted() {
        $json = Json::decode('{"name":"test","count":5}');
        $json->setTypeMap(['name' => SimpleUser::class]);
        $this->assertEquals('test', $json->get('name'));
    }

    public function testSetTypeMapArrayWithScalarItems() {
        $json = Json::decode('{"tags":["a","b","c"]}');
        $json->setTypeMap(['tags' => [SimpleUser::class]]);
        $tags = $json->get('tags');
        $this->assertEquals('a', $tags[0]);
    }

    public function testToJsonFileInvalidName() {
        $this->expectException(JsonException::class);
        $json = new Json(['x' => 1]);
        $json->toJsonFile('', '/tmp');
    }

    public function testToJsonFileInvalidPath() {
        $this->expectException(JsonException::class);
        $json = new Json(['x' => 1]);
        $json->toJsonFile('test', '');
    }

    public function testToJsonFileAlreadyExists() {
        $path = sys_get_temp_dir();
        $json = new Json(['x' => 1]);
        $json->toJsonFile('coverage-test', $path, true);
        $this->expectException(JsonException::class);
        $json->toJsonFile('coverage-test', $path, false);
    }

    public function testToJsonFileOverride() {
        $path = sys_get_temp_dir();
        $json = new Json(['x' => 1]);
        $json->toJsonFile('coverage-override', $path, true);
        $json2 = new Json(['y' => 2]);
        $json2->toJsonFile('coverage-override', $path, true);
        $loaded = Json::fromJsonFile($path . DIRECTORY_SEPARATOR . 'coverage-override.json');
        $this->assertEquals(2, $loaded->get('y'));
        unlink($path . DIRECTORY_SEPARATOR . 'coverage-override.json');
    }

    public function testSetTypeMapArrayKeyNotInJson() {
        $json = Json::decode('{"name":"test"}');
        $json->setTypeMap(['missing' => SimpleUser::class]);
        $this->assertNull($json->get('missing'));
    }

    public function testDeserializePropertyJsonTypeOnConstructorProperty() {
        $json = '{"name":"Team A","manager":{"username":"boss","email":"b@c.com"}}';
        $obj = Json::decodeAs($json, WithPropertyJsonType::class);
        $this->assertInstanceOf(SimpleUser::class, $obj->getManager());
        $this->assertEquals('boss', $obj->getManager()->getUsername());
    }

    public function testSetterWithJsonTypeAttribute() {
        $json = '{"items":[{"name":"A","qty":1}],"lead":{"username":"x","email":"x@y.com"}}';
        $obj = Json::decodeAs($json, WithSetterJsonType::class);
        $this->assertCount(1, $obj->getItems());
        $this->assertInstanceOf(LineItem::class, $obj->getItems()[0]);
        $this->assertInstanceOf(SimpleUser::class, $obj->getLead());
    }

    public function testHydrateByJsonTypeScalarReturnedAsIs() {
        $json = Json::decode('{"name":"test","manager":"not-an-object"}');
        $obj = JsonDeserializer::deserialize($json, WithPropertyJsonType::class);
        $this->assertEquals('not-an-object', $obj->getManager());
    }

    public function testAddBooleanReturnsFalseForNonBool() {
        $json = new Json();
        $this->assertFalse($json->addBoolean('key', 'not-a-bool'));
    }
}
