<?php

require_once __DIR__ . '/../Enums/UserRoleEnum.php';
require_once __DIR__ . '/../Enums/UserStatusEnum.php';
require_once __DIR__ . '/../ValueObjects/UserId.php';
require_once __DIR__ . '/../ValueObjects/UserName.php';
require_once __DIR__ . '/../ValueObjects/UserEmail.php';
require_once __DIR__ . '/../ValueObjects/UserPassword.php';

final class UserModel
{
    private UserId $id;
    private UserName $name;
    private UserEmail $email;
    private UserPassword $password;
    private UserRoleEnum $role;
    private UserStatusEnum $status;

    private function __construct(
        UserId $id,
        UserName $name,
        UserEmail $email,
        UserPassword $password,
        UserRoleEnum $role,
        UserStatusEnum $status
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->status = $status;
    }

    public static function create(
        UserId $id,
        UserName $name,
        UserEmail $email,
        UserPassword $password,
        UserRoleEnum $role
    ): self {
        return new self($id, $name, $email, $password, $role, UserStatusEnum::PENDING);
    }

    public static function reconstitute(
        UserId $id,
        UserName $name,
        UserEmail $email,
        UserPassword $password,
        UserRoleEnum $role,
        UserStatusEnum $status
    ): self {
        return new self($id, $name, $email, $password, $role, $status);
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function name(): UserName
    {
        return $this->name;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function password(): UserPassword
    {
        return $this->password;
    }

    public function role(): UserRoleEnum
    {
        return $this->role;
    }

    public function status(): UserStatusEnum
    {
        return $this->status;
    }

    public function withUpdatedData(
        UserName $name,
        UserEmail $email,
        UserRoleEnum $role,
        UserStatusEnum $status
    ): self {
        return new self($this->id, $name, $email, $this->password, $role, $status);
    }

    public function changePassword(UserPassword $password): self
    {
        return new self($this->id, $this->name, $this->email, $password, $this->role, $this->status);
    }
}
