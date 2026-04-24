<?php

final class Flash
{
    public static function start(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['_flash'])) {
            $_SESSION['_flash'] = [];
        }
    }

    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION['_flash'][$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();

        if (!array_key_exists($key, $_SESSION['_flash'])) {
            return $default;
        }

        $value = $_SESSION['_flash'][$key];
        unset($_SESSION['_flash'][$key]);

        return $value;
    }

    public static function setOld(array $form): void
    {
        self::set('old', $form);
    }

    public static function old(): array
    {
        $old = self::get('old', []);

        return is_array($old) ? $old : [];
    }

    public static function setErrors(array $errors): void
    {
        self::set('errors', $errors);
    }

    public static function errors(): array
    {
        $errors = self::get('errors', []);

        return is_array($errors) ? $errors : [];
    }

    public static function setMessage(string $message): void
    {
        self::set('message', $message);
    }

    public static function message(): ?string
    {
        $message = self::get('message');

        return is_string($message) ? $message : null;
    }

    public static function setSuccess(string $message): void
    {
        self::set('success', $message);
    }

    public static function success(): ?string
    {
        $success = self::get('success');

        return is_string($success) ? $success : null;
    }
}
