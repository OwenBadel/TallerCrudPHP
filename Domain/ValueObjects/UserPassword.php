<?php

final class UserPassword
{
    private string $value;

    private function __construct(string $value)
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException('La contrasena del usuario no puede estar vacia.');
        }

        $this->value = $value;
    }

    public static function fromPlainText(string $plainText): self
    {
        if (mb_strlen($plainText) < 8) {
            throw new InvalidArgumentException('La contrasena debe tener al menos 8 caracteres.');
        }

        return new self(password_hash($plainText, PASSWORD_BCRYPT));
    }

    public static function fromHashed(string $hashedPassword): self
    {
        return new self($hashedPassword);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function verify(string $plainText): bool
    {
        return password_verify($plainText, $this->value);
    }
}
