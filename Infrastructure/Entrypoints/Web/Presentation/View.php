<?php

final class View
{
    public static function render(string $template, array $data = []): void
    {
        $file = __DIR__ . '/Views/' . $template . '.php';

        if (!is_file($file)) {
            throw new RuntimeException(sprintf('No existe la vista "%s".', $template));
        }

        extract($data, EXTR_SKIP);
        require $file;
    }

    public static function redirect(string $route): void
    {
        header('Location: ?route=' . urlencode($route));
        exit;
    }
}
