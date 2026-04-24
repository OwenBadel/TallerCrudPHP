<?php

interface DeleteUserUseCase
{
    public function execute(DeleteUserCommand $command): void;
}
