<?php

require_once __DIR__.'/../vendor/autoload.php';

use WebFiori\Json\Json;
use WebFiori\Json\JsonType;

// --- Simple typed deserialization ---

class User {
    public function __construct(
        private string $username,
        private string $email
    ) {
    }
    public function getEmail(): string {
        return $this->email;
    }
    public function getUsername(): string {
        return $this->username;
    }
}

$user = Json::decodeAs('{"username":"ibrahim","email":"a@b.com"}', User::class);
echo $user->getUsername()."\n"; // ibrahim
echo $user->getEmail()."\n";   // a@b.com

// --- Nested objects with #[JsonType] for arrays ---

class LineItem {
    public function __construct(
        private string $name,
        private int $qty
    ) {
    }
    public function getName(): string {
        return $this->name;
    }
    public function getQty(): int {
        return $this->qty;
    }
}

class Order {
    public function __construct(
        private User $customer,
        #[JsonType(LineItem::class, isArray: true)]
        private array $items
    ) {
    }
    public function getCustomer(): User {
        return $this->customer;
    }
    public function getItems(): array {
        return $this->items;
    }
}

$jsonStr = '{"customer":{"username":"ibrahim","email":"a@b.com"},"items":[{"name":"Keyboard","qty":2},{"name":"Mouse","qty":1}]}';
$order = Json::decodeAs($jsonStr, Order::class);

echo $order->getCustomer()->getUsername()."\n"; // ibrahim
echo count($order->getItems())."\n";            // 2
echo $order->getItems()[0]->getName()."\n";     // Keyboard

// --- Runtime type mapping via setTypeMap ---

$json = Json::decode($jsonStr);
$json->setTypeMap([
    'customer' => User::class,
    'items' => [LineItem::class],
]);

$customer = $json->get('customer');
echo get_class($customer)."\n";            // User
echo $customer->getUsername()."\n";        // ibrahim

$items = $json->get('items');
echo get_class($items[0])."\n";            // LineItem
echo $items[0]->getName().' x'.$items[0]->getQty()."\n"; // Keyboard x2
