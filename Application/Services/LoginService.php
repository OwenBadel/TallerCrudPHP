<?php

require_once __DIR__ . '/../Ports/In/LoginUseCase.php';
require_once __DIR__ . '/../Ports/Out/GetUserByEmailPort.php';

final class LoginService implements LoginUseCase
{
    private GetUserByEmailPort $getUserByEmailPort;

    public function __construct(GetUserByEmailPort $getUserByEmailPort)
    {
        $this->getUserByEmailPort = $getUserByEmailPort;
    }

    public function execute(LoginCommand $command): UserModel
    {
        $userEmail = new UserEmail($command->email);
        $user = $this->getUserByEmailPort->getByEmail($userEmail);

        if ($user === null) {
            throw InvalidCredentialsException::becauseCredentialsAreInvalid();
        }

        if (!$user->password()->verify($command->password)) {
            throw InvalidCredentialsException::becauseCredentialsAreInvalid();
        }

        if ($user->status() !== UserStatusEnum::ACTIVE) {
            throw InvalidCredentialsException::becauseUserIsInactive();
        }

        return $user;
    }
}
