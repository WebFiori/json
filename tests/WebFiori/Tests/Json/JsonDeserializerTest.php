<?php

namespace WebFiori\Tests\Json;

use PHPUnit\Framework\TestCase;
use WebFiori\Json\Json;
use WebFiori\Json\JsonDeserializer;
use WebFiori\Json\JsonException;
use WebFiori\Json\JsonProperty;
use WebFiori\Json\JsonType;
use WebFiori\Json\JsonIgnore;

class SimpleUser {
    public function __construct(
        private string $username,
        private string $email
    ) {}
    public function getUsername(): string { return $this->username; }
    public function getEmail(): string { return $this->email; }
}

class UserWithDefaults {
    public function __construct(
        private string $username,
        private string $email = 'default@example.com'
    ) {}
    public function getUsername(): string { return $this->username; }
    public function getEmail(): string { return $this->email; }
}

class NullableUser {
    public function __construct(
        private string $username,
        private ?string $nickname = null
    ) {}
    public function getUsername(): string { return $this->username; }
    public function getNickname(): ?string { return $this->nickname; }
}

class Address {
    public function __construct(
        private string $city,
        private string $country
    ) {}
    public function getCity(): string { return $this->city; }
    public function getCountry(): string { return $this->country; }
}

class LineItem {
    public function __construct(
        private string $name,
        private int $qty
    ) {}
    public function getName(): string { return $this->name; }
    public function getQty(): int { return $this->qty; }
}

class Order {
    public function __construct(
        private SimpleUser $customer,
        private Address $shippingAddress,
        #[JsonType(LineItem::class, isArray: true)]
        private array $items
    ) {}
    public function getCustomer(): SimpleUser { return $this->customer; }
    public function getShippingAddress(): Address { return $this->shippingAddress; }
    public function getItems(): array { return $this->items; }
}

class WithFromJson {
    private string $value;
    private function __construct(string $value) { $this->value = $value; }
    public static function fromJSON(Json $json): self {
        return new self($json->get('val') . '-custom');
    }
    public function getValue(): string { return $this->value; }
}

class WithSetter {
    private string $name = '';
    private string $title = '';
    public function setName(string $name): void { $this->name = $name; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function getName(): string { return $this->name; }
    public function getTitle(): string { return $this->title; }
}

class WithPublicProps {
    public string $name = '';
    public int $age = 0;
}

class WithTypedPublicProp {
    public SimpleUser $user;
    public string $label = '';
}

class WithJsonTypeProp {
    #[JsonType(LineItem::class, isArray: true)]
    public array $items = [];
    public string $status = '';
}

class NestedDeep {
    public function __construct(
        private Order $order,
        private string $note
    ) {}
    public function getOrder(): Order { return $this->order; }
    public function getNote(): string { return $this->note; }
}

class WithJsonPropertyOnGetter {
    private string $name;
    private int $value;
    public function __construct(string $name, int $value) {
        $this->name = $name;
        $this->value = $value;
    }
    #[JsonProperty('display_name')]
    public function getName(): string { return $this->name; }
    public function getValue(): int { return $this->value; }
}

class WithJsonPropertyOnProp {
    #[JsonProperty('item_sku')]
    public string $sku = '';
    public string $label = '';
}

class JsonDeserializerTest extends TestCase {

    // --- decodeAs basic ---

    public function testDecodeAsSimple() {
        $user = Json::decodeAs('{"username":"ibrahim","email":"a@b.com"}', SimpleUser::class);
        $this->assertInstanceOf(SimpleUser::class, $user);
        $this->assertEquals('ibrahim', $user->getUsername());
        $this->assertEquals('a@b.com', $user->getEmail());
    }

    public function testDecodeAsWithDefaults() {
        $user = Json::decodeAs('{"username":"ibrahim"}', UserWithDefaults::class);
        $this->assertEquals('ibrahim', $user->getUsername());
        $this->assertEquals('default@example.com', $user->getEmail());
    }

    public function testDecodeAsWithNullable() {
        $user = Json::decodeAs('{"username":"ibrahim"}', NullableUser::class);
        $this->assertEquals('ibrahim', $user->getUsername());
        $this->assertNull($user->getNickname());
    }

    public function testDecodeAsNonExistentClass() {
        $this->expectException(JsonException::class);
        Json::decodeAs('{"x":1}', 'NonExistentClass12345');
    }

    public function testDecodeAsMissingRequiredParam() {
        $this->expectException(JsonException::class);
        Json::decodeAs('{"email":"a@b.com"}', SimpleUser::class);
    }

    // --- fromJSON factory ---

    public function testDecodeAsWithFromJson() {
        $obj = Json::decodeAs('{"val":"hello"}', WithFromJson::class);
        $this->assertEquals('hello-custom', $obj->getValue());
    }

    // --- Nested objects ---

    public function testDecodeAsNestedObject() {
        $json = '{"customer":{"username":"ibrahim","email":"a@b.com"},"shippingAddress":{"city":"Riyadh","country":"SA"},"items":[{"name":"KB","qty":2}]}';
        $order = Json::decodeAs($json, Order::class);

        $this->assertInstanceOf(SimpleUser::class, $order->getCustomer());
        $this->assertEquals('ibrahim', $order->getCustomer()->getUsername());
        $this->assertInstanceOf(Address::class, $order->getShippingAddress());
        $this->assertEquals('Riyadh', $order->getShippingAddress()->getCity());
        $this->assertCount(1, $order->getItems());
        $this->assertInstanceOf(LineItem::class, $order->getItems()[0]);
        $this->assertEquals('KB', $order->getItems()[0]->getName());
        $this->assertEquals(2, $order->getItems()[0]->getQty());
    }

    public function testDecodeAsDeepNesting() {
        $json = '{"order":{"customer":{"username":"ibrahim","email":"a@b.com"},"shippingAddress":{"city":"Riyadh","country":"SA"},"items":[{"name":"X","qty":1}]},"note":"urgent"}';
        $deep = Json::decodeAs($json, NestedDeep::class);

        $this->assertEquals('urgent', $deep->getNote());
        $this->assertInstanceOf(Order::class, $deep->getOrder());
        $this->assertEquals('ibrahim', $deep->getOrder()->getCustomer()->getUsername());
    }

    // --- Setter-based hydration ---

    public function testDecodeAsWithSetters() {
        $obj = Json::decodeAs('{"name":"Ibrahim","title":"Dev"}', WithSetter::class);
        $this->assertEquals('Ibrahim', $obj->getName());
        $this->assertEquals('Dev', $obj->getTitle());
    }

    // --- Public property hydration ---

    public function testDecodeAsWithPublicProps() {
        $obj = Json::decodeAs('{"name":"Ibrahim","age":30}', WithPublicProps::class);
        $this->assertEquals('Ibrahim', $obj->name);
        $this->assertEquals(30, $obj->age);
    }

    public function testDecodeAsWithTypedPublicProp() {
        $obj = Json::decodeAs('{"user":{"username":"ib","email":"x@y.com"},"label":"test"}', WithTypedPublicProp::class);
        $this->assertInstanceOf(SimpleUser::class, $obj->user);
        $this->assertEquals('ib', $obj->user->getUsername());
        $this->assertEquals('test', $obj->label);
    }

    public function testDecodeAsWithJsonTypeProp() {
        $obj = Json::decodeAs('{"items":[{"name":"A","qty":1},{"name":"B","qty":2}],"status":"done"}', WithJsonTypeProp::class);
        $this->assertCount(2, $obj->items);
        $this->assertInstanceOf(LineItem::class, $obj->items[0]);
        $this->assertEquals('A', $obj->items[0]->getName());
        $this->assertEquals('done', $obj->status);
    }

    // --- setTypeMap ---

    public function testSetTypeMapSingleObject() {
        $json = Json::decode('{"customer":{"username":"ibrahim","email":"a@b.com"},"total":100}');
        $json->setTypeMap(['customer' => SimpleUser::class]);

        $customer = $json->get('customer');
        $this->assertInstanceOf(SimpleUser::class, $customer);
        $this->assertEquals('ibrahim', $customer->getUsername());
        $this->assertEquals(100, $json->get('total'));
    }

    public function testSetTypeMapArrayOfObjects() {
        $json = Json::decode('{"items":[{"name":"KB","qty":2},{"name":"Mouse","qty":1}]}');
        $json->setTypeMap(['items' => [LineItem::class]]);

        $items = $json->get('items');
        $this->assertCount(2, $items);
        $this->assertInstanceOf(LineItem::class, $items[0]);
        $this->assertEquals('KB', $items[0]->getName());
        $this->assertEquals(1, $items[1]->getQty());
    }

    public function testSetTypeMapNoMapping() {
        $json = Json::decode('{"name":"test"}');
        $json->setTypeMap([]);
        $this->assertEquals('test', $json->get('name'));
    }

    // --- setDefaults / resetDefaults ---

    public function testSetDefaultsStyle() {
        Json::setDefaults(style: 'camel');
        $json = new Json(['first-name' => 'Ibrahim']);
        $this->assertEquals('{"firstName":"Ibrahim"}', $json->toJSONString());
        Json::resetDefaults();
    }

    public function testSetDefaultsCase() {
        Json::setDefaults(style: 'snake', case: 'upper');
        $json = new Json(['firstName' => 'Ibrahim']);
        $this->assertEquals('{"FIRST_NAME":"Ibrahim"}', $json->toJSONString());
        Json::resetDefaults();
    }

    public function testSetDefaultsFormatted() {
        Json::setDefaults(formatted: true);
        $json = new Json(['x' => 1]);
        $this->assertTrue($json->isFormatted());
        Json::resetDefaults();
    }

    public function testResetDefaults() {
        Json::setDefaults(style: 'snake', case: 'upper', formatted: true);
        Json::resetDefaults();
        $json = new Json(['first-name' => 'Ibrahim']);
        $this->assertEquals('{"first-name":"Ibrahim"}', $json->toJSONString());
        $this->assertFalse($json->isFormatted());
    }

    public function testConstructorOverridesDefaults() {
        Json::setDefaults(style: 'camel');
        $json = new Json(['first-name' => 'Ibrahim'], 'snake');
        $this->assertEquals('{"first_name":"Ibrahim"}', $json->toJSONString());
        Json::resetDefaults();
    }

    // --- #[JsonProperty] in encoding ---

    public function testJsonPropertyOnGetter() {
        $json = new Json([], 'none');
        $obj = new WithJsonPropertyOnGetter('Test', 42);
        $json->addObject('item', $obj);
        $str = $json->toJSONString();
        $this->assertStringContainsString('"display_name":"Test"', $str);
        $this->assertStringContainsString('"Value":42', $str);
    }

    public function testJsonPropertyOnPublicProp() {
        $json = new Json([], 'camel');
        $obj = new WithJsonPropertyOnProp();
        $obj->sku = 'X-100';
        $obj->label = 'hello';
        $json->addObject('item', $obj);
        $str = $json->toJSONString();
        $this->assertStringContainsString('"item_sku":"X-100"', $str);
        $this->assertStringContainsString('"label":"hello"', $str);
    }

    public function testJsonPropertyImmuneToStyleConversion() {
        $json = new Json([], 'snake');
        $obj = new WithJsonPropertyOnGetter('Test', 42);
        $json->addObject('item', $obj);
        $str = $json->toJSONString();
        // display_name stays as-is even with snake style
        $this->assertStringContainsString('"display_name":"Test"', $str);
        // Value normalizes to value in snake
        $this->assertStringContainsString('"value":42', $str);
    }

    // --- Associative array auto-detection ---

    public function testAssociativeArrayAutoDetected() {
        $json = new Json();
        $json->addArray('data', ['key1' => 'val1', 'key2' => 'val2']);
        $str = $json->toJSONString();
        $this->assertStringContainsString('"key1":"val1"', $str);
        $this->assertStringContainsString('"key2":"val2"', $str);
        // Should be an object, not array
        $this->assertStringNotContainsString('["val1","val2"]', $str);
    }

    public function testIndexedArrayStaysArray() {
        $json = new Json();
        $json->addArray('data', ['a', 'b', 'c']);
        $this->assertEquals('{"data":["a","b","c"]}', $json->toJSONString());
    }

    public function testEmptyArrayStaysArray() {
        $json = new Json();
        $json->addArray('data', []);
        $this->assertEquals('{"data":[]}', $json->toJSONString());
    }

    // --- Property nameIsExplicit ---

    public function testPropertyExplicitNameNotConverted() {
        $prop = new \WebFiori\Json\Property('my_name', 'val');
        $prop->setNameIsExplicit(true);
        $this->assertTrue($prop->isNameExplicit());
        $prop->setStyle('camel');
        // Name should NOT be converted
        $this->assertEquals('my_name', $prop->getName());
    }

    public function testPropertyNonExplicitNameConverted() {
        $prop = new \WebFiori\Json\Property('my_name', 'val');
        $this->assertFalse($prop->isNameExplicit());
        $prop->setStyle('camel');
        $this->assertEquals('myName', $prop->getName());
    }

    // --- CaseConverter camelCase fix ---

    public function testCamelCaseLowercasesFirstChar() {
        $this->assertEquals('name', \WebFiori\Json\CaseConverter::convert('Name', 'camel'));
        $this->assertEquals('unitPrice', \WebFiori\Json\CaseConverter::convert('UnitPrice', 'camel'));
        $this->assertEquals('myValue', \WebFiori\Json\CaseConverter::convert('my-value', 'camel'));
    }
}
