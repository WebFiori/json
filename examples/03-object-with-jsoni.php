<?php

require_once __DIR__.'/../vendor/autoload.php';

use WebFiori\Json\Json;
use WebFiori\Json\JsonI;

class User implements JsonI {
    public function __construct(
        private string $username,
        private string $email
    ) {
    }

    public function toJSON(): Json {
        return new Json(['username' => $this->username, 'email' => $this->email]);
    }
}

$json = new Json();
$user = new User('ibrahim', 'ibrahim@example.com');
$json->addObject('user', $user);

echo $json."\n";
// {"user":{"username":"ibrahim","email":"ibrahim@example.com"}}
