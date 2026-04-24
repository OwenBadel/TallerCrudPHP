<?php

final class UserResponse
{
    public readonly string $id;
    public readonly string $name;
    public readonly string $email;
    public readonly string $role;
    public readonly string $status;

    public function __construct(string $id, string $name, string $email, string $role, string $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        $this->status = $status;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
        ];
    }
}
