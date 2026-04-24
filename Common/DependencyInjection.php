<?php

final class DependencyInjection
{
    public static function boot(): void
    {
        ClassLoader::register();
        Flash::start();
    }

    public function getConnection(): Connection
    {
        return Connection::getInstance();
    }

    public function getPdo(): PDO
    {
        return $this->getConnection()->getPdo();
    }

    public function getUserPersistenceMapper(): UserPersistenceMapper
    {
        return new UserPersistenceMapper();
    }

    public function getUserRepository(): UserRepositoryMySQL
    {
        return new UserRepositoryMySQL($this->getPdo(), $this->getUserPersistenceMapper());
    }

    public function getCreateUserUseCase(): CreateUserUseCase
    {
        $repository = $this->getUserRepository();

        return new CreateUserService($repository, $repository);
    }

    public function getUpdateUserUseCase(): UpdateUserUseCase
    {
        $repository = $this->getUserRepository();

        return new UpdateUserService($repository, $repository);
    }

    public function getDeleteUserUseCase(): DeleteUserUseCase
    {
        $repository = $this->getUserRepository();

        return new DeleteUserService($repository, $repository);
    }

    public function getLoginUseCase(): LoginUseCase
    {
        return new LoginService($this->getUserRepository());
    }

    public function getUserWebMapper(): UserWebMapper
    {
        return new UserWebMapper();
    }

    public function getEmailNotificationService(): EmailNotificationService
    {
        return new EmailNotificationService();
    }

    public function getUserController(): UserController
    {
        $repository = $this->getUserRepository();

        return new UserController(
            $this->getCreateUserUseCase(),
            $this->getUpdateUserUseCase(),
            $this->getDeleteUserUseCase(),
            $this->getLoginUseCase(),
            $repository,
            $repository,
            $this->getUserWebMapper()
        );
    }
}
