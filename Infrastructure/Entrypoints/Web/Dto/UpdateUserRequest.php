<?php

final class UpdateUserRequest
{
    public readonly string $id;
    public readonly string $name;
    public readonly string $email;
    public readonly string $password;
    public readonly string $role;
    public readonly string $status;

    public function __construct(string $id, string $name, string $email, string $password, string $role, string $status)
    {
        $this->id = trim($id);
        $this->name = trim($name);
        $this->email = trim($email);
        $this->password = $password;
        $this->role = trim($role);
        $this->status = trim($status);
    }
}
