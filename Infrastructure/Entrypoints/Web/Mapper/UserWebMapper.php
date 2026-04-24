<?php

final class UserWebMapper
{
    public function fromCreateRequestToCommand(CreateUserRequest $request): CreateUserCommand
    {
        return new CreateUserCommand($request->id, $request->name, $request->email, $request->password, $request->role);
    }

    public function fromUpdateRequestToCommand(UpdateUserRequest $request): UpdateUserCommand
    {
        return new UpdateUserCommand($request->id, $request->name, $request->email, $request->password, $request->role, $request->status);
    }

    public function fromLoginRequestToCommand(LoginWebRequest $request): LoginCommand
    {
        return new LoginCommand($request->email, $request->password);
    }

    public function fromIdToGetByIdQuery(string $id): GetUserByIdQuery
    {
        return new GetUserByIdQuery($id);
    }

    public function fromIdToDeleteCommand(string $id): DeleteUserCommand
    {
        return new DeleteUserCommand($id);
    }

    public function fromModelToResponse(UserModel $user): UserResponse
    {
        return new UserResponse(
            $user->id()->value(),
            $user->name()->value(),
            $user->email()->value(),
            $user->role()->value,
            $user->status()->value
        );
    }

    /**
     * @param UserModel[] $users
     * @return UserResponse[]
     */
    public function fromModelsToResponses(array $users): array
    {
        $responses = [];

        foreach ($users as $user) {
            $responses[] = $this->fromModelToResponse($user);
        }

        return $responses;
    }
}
