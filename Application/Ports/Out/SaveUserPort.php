<?php

interface SaveUserPort
{
    public function save(UserModel $user): UserModel;
}
