<?php

declare(strict_types=1);


namespace App\Back\Shared\Domain\ValueObjects;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Ramsey\Uuid\UuidFactory;

class Uuid extends UuidFactory
{
    public function __construct(protected string $value)
    {
        $this->ensureIsValidUuid($value);
        parent::__construct();
    }

    public static function random(): self
    {
        return new static(RamseyUuid::uuid4()->toString());
    }

    public static function fromUuid(string $uuid): self
    {
        return new static($uuid);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Uuid $other): bool
    {
        return $this->value() === $other->value();
    }

    public function __toString(): string
    {
        return $this->value();
    }

    private function ensureIsValidUuid(string $id): void
    {
        if (!RamseyUuid::isValid($id)) {
            throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', static::class, $id));
        }
    }
}