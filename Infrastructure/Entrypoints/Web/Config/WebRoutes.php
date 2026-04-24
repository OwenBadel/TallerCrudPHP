<?php

final class WebRoutes
{
    public static function all(): array
    {
        return [
            'home' => ['method' => 'GET', 'action' => 'home', 'public' => true],
            'users.index' => ['method' => 'GET', 'action' => 'index', 'public' => true],
            'users.create' => ['method' => 'GET', 'action' => 'create', 'public' => true],
            'users.store' => ['method' => 'POST', 'action' => 'store', 'public' => true],
            'users.show' => ['method' => 'GET', 'action' => 'show', 'public' => true],
            'users.edit' => ['method' => 'GET', 'action' => 'edit', 'public' => true],
            'users.update' => ['method' => 'POST', 'action' => 'update', 'public' => true],
            'users.delete' => ['method' => 'POST', 'action' => 'delete', 'public' => true],
            'auth.login' => ['method' => 'GET', 'action' => 'login', 'public' => true],
            'auth.authenticate' => ['method' => 'POST', 'action' => 'authenticate', 'public' => true],
            'auth.logout' => ['method' => 'GET', 'action' => 'logout', 'public' => true],
            'auth.forgot' => ['method' => 'GET', 'action' => 'forgot', 'public' => true],
            'auth.forgot.send' => ['method' => 'POST', 'action' => 'forgotSend', 'public' => true],
        ];
    }
}
