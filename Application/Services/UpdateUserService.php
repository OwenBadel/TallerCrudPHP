<?php

require_once __DIR__ . '/../Ports/In/UpdateUserUseCase.php';
require_once __DIR__ . '/../Ports/Out/GetUserByIdPort.php';
require_once __DIR__ . '/../Ports/Out/SaveUserPort.php';

final class UpdateUserService implements UpdateUserUseCase
{
    private GetUserByIdPort $getUserByIdPort;
    private SaveUserPort $saveUserPort;

    public function __construct(
        GetUserByIdPort $getUserByIdPort,
        SaveUserPort $saveUserPort
    ) {
        $this->getUserByIdPort = $getUserByIdPort;
        $this->saveUserPort = $saveUserPort;
    }

    public function execute(UpdateUserCommand $command): UserModel
    {
        $userId = new UserId($command->id);
        $currentUser = $this->getUserByIdPort->getById($userId);

        if ($currentUser === null) {
            throw UserNotFoundException::byId($command->id);
        }

        $updatedPassword = trim($command->password) !== ''
            ? UserPassword::fromPlainText($command->password)
            : $currentUser->password();

        $updatedUser = UserModel::reconstitute(
            $currentUser->id(),
            new UserName($command->name),
            new UserEmail($command->email),
            $updatedPassword,
            $this->resolveRole($command->role, $currentUser->role()),
            $this->resolveStatus($command->status, $currentUser->status())
        );

        return $this->saveUserPort->save($updatedUser);
    }

    private function resolveRole(string $role, UserRoleEnum $fallback): UserRoleEnum
    {
        if (trim($role) === '') {
            return $fallback;
        }

        $userRole = UserRoleEnum::tryFrom(strtoupper(trim($role)));
        if ($userRole === null) {
            throw new InvalidArgumentException('El rol del usuario no es valido.');
        }

        return $userRole;
    }

    private function resolveStatus(string $status, UserStatusEnum $fallback): UserStatusEnum
    {
        if (trim($status) === '') {
            return $fallback;
        }

        $userStatus = UserStatusEnum::tryFrom(strtoupper(trim($status)));
        if ($userStatus === null) {
            throw new InvalidArgumentException('El estado del usuario no es valido.');
        }

        return $userStatus;
    }
}
