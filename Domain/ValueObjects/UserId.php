<?php

final class UserId
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);
        if ($value === '') {
            throw new InvalidArgumentException('El id del usuario no puede estar vacio.');
        }

        $this->value = $value;
    }

    public static function generate(): self
    {
        return new self(bin2hex(random_bytes(16)));
    }

    public function value(): string
    {
        return $this->value;
    }
}
