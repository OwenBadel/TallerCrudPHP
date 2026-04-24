<?php

final class UserPersistenceMapper
{
    public function fromRowToModel(array $row): UserModel
    {
        return UserModel::reconstitute(
            new UserId((string) $row['id']),
            new UserName((string) $row['name']),
            new UserEmail((string) $row['email']),
            UserPassword::fromHashed((string) $row['password']),
            $this->mapRole((string) $row['role']),
            $this->mapStatus((string) $row['status'])
        );
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     * @return UserModel[]
     */
    public function fromRowsToModels(array $rows): array
    {
        $models = [];

        foreach ($rows as $row) {
            $models[] = $this->fromRowToModel($row);
        }

        return $models;
    }

    private function mapRole(string $role): UserRoleEnum
    {
        $mappedRole = UserRoleEnum::tryFrom(strtoupper(trim($role)));

        if ($mappedRole === null) {
            return UserRoleEnum::default();
        }

        return $mappedRole;
    }

    private function mapStatus(string $status): UserStatusEnum
    {
        $mappedStatus = UserStatusEnum::tryFrom(strtoupper(trim($status)));

        if ($mappedStatus === null) {
            return UserStatusEnum::PENDING;
        }

        return $mappedStatus;
    }
}
