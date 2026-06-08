<?php
namespace WebFiori\Tests;

use WebFiori\Json\JsonIgnore;

class ObjWithNullFalseGetters {
    private ?string $middleName;
    private bool $active;
    private string $name;

    public function __construct(string $name, ?string $middleName, bool $active) {
        $this->name = $name;
        $this->middleName = $middleName;
        $this->active = $active;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getMiddleName(): ?string {
        return $this->middleName;
    }

    public function getActive(): bool {
        return $this->active;
    }

    #[JsonIgnore]
    public function getSecret(): string {
        return 'hidden-value';
    }
}
