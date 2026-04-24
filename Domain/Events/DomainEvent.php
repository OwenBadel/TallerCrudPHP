<?php

abstract class DomainEvent
{
    private string $aggregateId;
    private DateTimeImmutable $occurredOn;

    public function __construct(string $aggregateId)
    {
        $this->aggregateId = $aggregateId;
        $this->occurredOn = new DateTimeImmutable();
    }

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    abstract public function eventName(): string;
}
