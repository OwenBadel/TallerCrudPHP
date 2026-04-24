<?php

interface CreateUserUseCase
{
    public function execute(CreateUserCommand $command): UserModel;
}
