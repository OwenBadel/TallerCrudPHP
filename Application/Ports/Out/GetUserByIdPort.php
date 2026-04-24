<?php

interface GetUserByIdPort
{
    public function getById(UserId $id): ?UserModel;
}
