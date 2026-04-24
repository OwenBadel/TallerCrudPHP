<?php

final class LoginWebRequest
{
    public readonly string $email;
    public readonly string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = trim($email);
        $this->password = $password;
    }
}
