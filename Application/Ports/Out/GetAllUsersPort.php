<?php

interface GetAllUsersPort
{
    /**
     * @return UserModel[]
     */
    public function getAll(): array;
}
