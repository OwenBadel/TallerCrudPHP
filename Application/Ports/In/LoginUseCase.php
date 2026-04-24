<?php

interface LoginUseCase
{
    public function execute(LoginCommand $command): UserModel;
}
