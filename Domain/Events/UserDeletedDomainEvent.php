<?php

require_once __DIR__ . '/DomainEvent.php';

final class UserDeletedDomainEvent extends DomainEvent
{
    public function eventName(): string
    {
        return 'user.deleted';
    }
}
