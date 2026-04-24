<?php

interface UpdateUserPort
{
    public function update(UserModel $user): UserModel;
}
