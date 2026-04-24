<?php

final class WebRoutes
{
    public static function all(): array
    {
        return [
            'home' => ['method' => 'GET', 'action' => 'home', 'public' => true],
            'users.index' => ['method' => 'GET', 'action' => 'index', 'public' => false],
            'users.create' => ['method' => 'GET', 'action' => 'create', 'public' => true],
            'users.store' => ['method' => 'POST', 'action' => 'store', 'public' => true],
            'users.show' => ['method' => 'GET', 'action' => 'show', 'public' => false],
            'users.edit' => ['method' => 'GET', 'action' => 'edit', 'public' => false],
            'users.update' => ['method' => 'POST', 'action' => 'update', 'public' => false],
            'users.delete' => ['method' => 'POST', 'action' => 'delete', 'public' => false],
            'auth.login' => ['method' => 'GET', 'action' => 'login', 'public' => true],
            'auth.authenticate' => ['method' => 'POST', 'action' => 'authenticate', 'public' => true],
            'auth.logout' => ['method' => 'GET', 'action' => 'logout', 'public' => true],
            'auth.forgot' => ['method' => 'GET', 'action' => 'forgot', 'public' => true],
            'auth.forgot.send' => ['method' => 'POST', 'action' => 'forgotSend', 'public' => true],
            'auth.forgot.code' => ['method' => 'GET', 'action' => 'forgotCode', 'public' => true],
            'auth.forgot.code.check' => ['method' => 'POST', 'action' => 'forgotCodeCheck', 'public' => true],
            'auth.forgot.reset' => ['method' => 'GET', 'action' => 'forgotReset', 'public' => true],
            'auth.forgot.reset.submit' => ['method' => 'POST', 'action' => 'forgotResetSubmit', 'public' => true],
        ];
    }
}
