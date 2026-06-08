<?php
namespace WebFiori\Tests;

use WebFiori\Json\JsonIgnore;

class ObjWithIgnoredProps {
    #[JsonIgnore]
    public string $internalId = 'abc-123';

    public string $name = 'Ibrahim';

    public ?string $email = null;
}
