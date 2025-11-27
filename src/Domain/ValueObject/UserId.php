<?php

namespace Domain\ValueObject;

final class UserId
{
    public function __construct(private string $value) {}

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(UserId $userId): bool
    {
        return $this->value === $userId->getValue();
    }
}
