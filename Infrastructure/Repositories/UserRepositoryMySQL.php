<?php

require_once __DIR__ . '/../../Application/Ports/Out/SaveUserPort.php';
require_once __DIR__ . '/../../Application/Ports/Out/GetUserByEmailPort.php';
require_once __DIR__ . '/../../Application/Ports/Out/GetUserByIdPort.php';
require_once __DIR__ . '/../../Application/Ports/Out/DeleteUserPort.php';
require_once __DIR__ . '/../../Application/Ports/Out/GetAllUsersPort.php';

final class UserRepositoryMySQL implements SaveUserPort, GetUserByEmailPort, GetUserByIdPort, DeleteUserPort, GetAllUsersPort
{
    private PDO $pdo;
    private UserPersistenceMapper $mapper;

    public function __construct(PDO $pdo, UserPersistenceMapper $mapper)
    {
        $this->pdo = $pdo;
        $this->mapper = $mapper;
    }

    public function save(UserModel $user): UserModel
    {
        $sql = 'INSERT INTO users (id, name, email, password, role, status) VALUES (:id, :name, :email, :password, :role, :status)';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            ':id' => $user->id()->value(),
            ':name' => $user->name()->value(),
            ':email' => $user->email()->value(),
            ':password' => $user->password()->value(),
            ':role' => $user->role()->value,
            ':status' => $user->status()->value,
        ]);

        return $this->getById($user->id()) ?? $user;
    }

    public function update(UserModel $user): UserModel
    {
        $sql = 'UPDATE users SET name = :name, email = :email, password = :password, role = :role, status = :status WHERE id = :id';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            ':id' => $user->id()->value(),
            ':name' => $user->name()->value(),
            ':email' => $user->email()->value(),
            ':password' => $user->password()->value(),
            ':role' => $user->role()->value,
            ':status' => $user->status()->value,
        ]);

        return $this->getById($user->id()) ?? $user;
    }

    public function getByEmail(UserEmail $email): ?UserModel
    {
        $sql = 'SELECT id, name, email, password, role, status FROM users WHERE email = :email LIMIT 1';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':email' => $email->value()]);
        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return $this->mapper->fromRowToModel($row);
    }

    public function getById(UserId $id): ?UserModel
    {
        $sql = 'SELECT id, name, email, password, role, status FROM users WHERE id = :id LIMIT 1';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $id->value()]);
        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return $this->mapper->fromRowToModel($row);
    }

    public function delete(UserId $id): void
    {
        $sql = 'DELETE FROM users WHERE id = :id';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $id->value()]);
    }

    public function getAll(): array
    {
        $sql = 'SELECT id, name, email, password, role, status FROM users ORDER BY name ASC';
        $statement = $this->pdo->query($sql);
        $rows = $statement->fetchAll();

        if ($rows === false) {
            return [];
        }

        return $this->mapper->fromRowsToModels($rows);
    }
}
