<?php

require_once __DIR__ . '/../Exceptions/InvalidUserEmailException.php';

final class UserEmail
{
    private string $value;

    public function __construct(string $value)
    {
        $normalizedValue = mb_strtolower(trim($value));

        if ($normalizedValue === '') {
            throw InvalidUserEmailException::becauseEmailIsEmpty();
        }

        if (filter_var($normalizedValue, FILTER_VALIDATE_EMAIL) === false) {
            throw InvalidUserEmailException::becauseFormatIsInvalid($value);
        }

        $this->value = $normalizedValue;
    }

    public function value(): string
    {
        return $this->value;
    }
}
