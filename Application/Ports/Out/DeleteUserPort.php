<?php

interface DeleteUserPort
{
    public function delete(UserId $id): void;
}
