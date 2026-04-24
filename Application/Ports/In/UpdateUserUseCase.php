<?php

interface UpdateUserUseCase
{
    public function execute(UpdateUserCommand $command): UserModel;
}
