<?php

require_once __DIR__ . '/../Ports/In/DeleteUserUseCase.php';
require_once __DIR__ . '/../Ports/Out/GetUserByIdPort.php';
require_once __DIR__ . '/../Ports/Out/DeleteUserPort.php';

final class DeleteUserService implements DeleteUserUseCase
{
    private GetUserByIdPort $getUserByIdPort;
    private DeleteUserPort $deleteUserPort;

    public function __construct(
        GetUserByIdPort $getUserByIdPort,
        DeleteUserPort $deleteUserPort
    ) {
        $this->getUserByIdPort = $getUserByIdPort;
        $this->deleteUserPort = $deleteUserPort;
    }

    public function execute(DeleteUserCommand $command): void
    {
        $userId = new UserId($command->id);

        if ($this->getUserByIdPort->getById($userId) === null) {
            throw UserNotFoundException::byId($command->id);
        }

        $this->deleteUserPort->delete($userId);
    }
}
