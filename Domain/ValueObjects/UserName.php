<?php

final class UserName
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if ($value === '') {
            throw new InvalidArgumentException('El nombre del usuario no puede estar vacio.');
        }

        if (mb_strlen($value) < 2) {
            throw new InvalidArgumentException('El nombre del usuario debe tener al menos 2 caracteres.');
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
