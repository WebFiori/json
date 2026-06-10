<?php

require_once __DIR__.'/../vendor/autoload.php';

use WebFiori\Json\Json;
use WebFiori\Json\JsonProperty;

// #[JsonProperty] overrides the derived name and is immune to style conversion

class Server {
    #[JsonProperty('host_name')]
    public string $hostname = 'localhost';

    public function getMaxConnections(): int {
        return 100;
    }

    #[JsonProperty('server_port')]
    public function getPort(): int {
        return 8080;
    }
}

// Even with camel style, #[JsonProperty] names are preserved as-is
$json = new Json([], 'camel');
$server = new Server();
$json->addObject('server', $server);
echo $json."\n";
// {"server":{"maxConnections":100,"host_name":"localhost","server_port":8080}}
// ^ "maxConnections" is normalized, but "host_name" and "server_port" stay as declared
