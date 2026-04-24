<?php

final class LoginCommand
{
    public string $email;
    public string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = trim($email);
        $this->password = $password;
    }
}
