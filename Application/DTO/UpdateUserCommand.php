<?php

final class UpdateUserCommand
{
    public string $id;
    public string $name;
    public string $email;
    public string $password;
    public string $role;
    public string $status;

    public function __construct(
        string $id,
        string $name,
        string $email,
        string $password,
        string $role,
        string $status
    ) {
        $this->id = trim($id);
        $this->name = trim($name);
        $this->email = trim($email);
        $this->password = $password;
        $this->role = trim($role);
        $this->status = trim($status);
    }
}
