<?php

require_once __DIR__ . '/DomainEvent.php';

final class UserCreatedDomainEvent extends DomainEvent
{
    public function eventName(): string
    {
        return 'user.created';
    }
}
