<?php

interface GetUserByEmailPort
{
    public function getByEmail(UserEmail $email): ?UserModel;
}
