<?php

namespace App\Domain\Shared;

abstract class EntityId
{
    private int $id;

    public function __construct(int $id)
    {
        if ($id < 0) {
            throw new EntityIdNegativeException();
        }
        $this->id = $id;
    }

    public function value(): int
    {
        return $this->id;
    }

    public function equals(EntityId $id): bool
    {
        return get_class($this) == get_class($id) && $this->value() === $id->value();
    }
}
