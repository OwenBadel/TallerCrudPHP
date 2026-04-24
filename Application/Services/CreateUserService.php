<?php

require_once __DIR__ . '/../Ports/In/CreateUserUseCase.php';
require_once __DIR__ . '/../Ports/Out/SaveUserPort.php';
require_once __DIR__ . '/../Ports/Out/GetUserByEmailPort.php';

final class CreateUserService implements CreateUserUseCase
{
    private SaveUserPort $saveUserPort;
    private GetUserByEmailPort $getUserByEmailPort;

    public function __construct(
        SaveUserPort $saveUserPort,
        GetUserByEmailPort $getUserByEmailPort
    ) {
        $this->saveUserPort = $saveUserPort;
        $this->getUserByEmailPort = $getUserByEmailPort;
    }

    public function execute(CreateUserCommand $command): UserModel
    {
        $userEmail = new UserEmail($command->email);
        if ($this->getUserByEmailPort->getByEmail($userEmail) !== null) {
            throw new InvalidArgumentException('Ya existe un usuario registrado con ese email.');
        }

        $user = UserModel::create(
            new UserId($command->id !== '' ? $command->id : UserId::generate()->value()),
            new UserName($command->name),
            $userEmail,
            UserPassword::fromPlainText($command->password),
            $this->resolveRole($command->role)
        );

        return $this->saveUserPort->save($user);
    }

    private function resolveRole(string $role): UserRoleEnum
    {
        if ($role === '') {
            return UserRoleEnum::default();
        }

        $userRole = UserRoleEnum::tryFrom(strtoupper(trim($role)));
        if ($userRole === null) {
            throw new InvalidArgumentException('El rol del usuario no es valido.');
        }

        return $userRole;
    }
}
