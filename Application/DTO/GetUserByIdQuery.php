<?php

final class GetUserByIdQuery
{
    public string $id;

    public function __construct(string $id)
    {
        $this->id = trim($id);
    }
}
