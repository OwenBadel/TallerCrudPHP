<?php

require_once __DIR__ . '/DomainEvent.php';

final class UserUpdatedDomainEvent extends DomainEvent
{
    public function eventName(): string
    {
        return 'user.updated';
    }
}
