<?php

final class UserController
{
    private CreateUserUseCase $createUserUseCase;
    private UpdateUserUseCase $updateUserUseCase;
    private DeleteUserUseCase $deleteUserUseCase;
    private LoginUseCase $loginUseCase;
    private GetAllUsersPort $getAllUsersPort;
    private GetUserByIdPort $getUserByIdPort;
    private UserWebMapper $userWebMapper;
    private EmailNotificationService $emailNotificationService;

    public function __construct(
        CreateUserUseCase $createUserUseCase,
        UpdateUserUseCase $updateUserUseCase,
        DeleteUserUseCase $deleteUserUseCase,
        LoginUseCase $loginUseCase,
        GetAllUsersPort $getAllUsersPort,
        GetUserByIdPort $getUserByIdPort,
        UserWebMapper $userWebMapper,
        EmailNotificationService $emailNotificationService
    ) {
        $this->createUserUseCase = $createUserUseCase;
        $this->updateUserUseCase = $updateUserUseCase;
        $this->deleteUserUseCase = $deleteUserUseCase;
        $this->loginUseCase = $loginUseCase;
        $this->getAllUsersPort = $getAllUsersPort;
        $this->getUserByIdPort = $getUserByIdPort;
        $this->userWebMapper = $userWebMapper;
        $this->emailNotificationService = $emailNotificationService;
    }

    public function home(): void
    {
        View::render('home', $this->baseViewData('Inicio'));
    }

    public function index(): void
    {
        $users = $this->userWebMapper->fromModelsToResponses($this->getAllUsersPort->getAll());

        View::render('users/list', array_merge($this->baseViewData('Usuarios'), [
            'users' => $users,
        ]));
    }

    public function create(): void
    {
        View::render('users/create', array_merge($this->baseFormViewData('Crear usuario'), [
            'roleOptions' => $this->roleOptions(),
        ]));
    }

    public function store(CreateUserRequest $request): void
    {
        $this->createUserUseCase->execute($this->userWebMapper->fromCreateRequestToCommand($request));

        $sent = $this->emailNotificationService->sendWelcome(
            $request->email,
            $request->name,
            $request->password,
            $request->role !== '' ? strtoupper($request->role) : UserRoleEnum::default()->value
        );

        if (!$sent) {
            Flash::setMessage('Usuario creado, pero no se pudo enviar el correo de bienvenida. Revisa storage/mail.log.');
        }

        Flash::setSuccess('Usuario creado correctamente.');
        View::redirect('auth.login');
    }

    public function show(string $id): void
    {
        $user = $this->getRequiredUser($id);

        View::render('users/show', array_merge($this->baseViewData('Detalle de usuario'), [
            'user' => $this->userWebMapper->fromModelToResponse($user),
        ]));
    }

    public function edit(string $id): void
    {
        $user = $this->getRequiredUser($id);

        View::render('users/edit', array_merge($this->baseFormViewData('Editar usuario'), [
            'user' => $this->userWebMapper->fromModelToResponse($user),
            'roleOptions' => $this->roleOptions(),
            'statusOptions' => $this->statusOptions(),
            'canManageSecurity' => $this->isAdminSession(),
        ]));
    }

    public function update(UpdateUserRequest $request): void
    {
        $sanitizedRequest = $request;

        if (!$this->isAdminSession()) {
            $sanitizedRequest = new UpdateUserRequest(
                $request->id,
                $request->name,
                $request->email,
                '',
                '',
                ''
            );
        }

        $this->updateUserUseCase->execute($this->userWebMapper->fromUpdateRequestToCommand($sanitizedRequest));
        Flash::setSuccess('Usuario actualizado correctamente.');
        View::redirect('users.index');
    }

    public function delete(string $id): void
    {
        $isCurrentUser = isset($_SESSION['auth']['id']) && (string) $_SESSION['auth']['id'] === $id;

        $this->deleteUserUseCase->execute($this->userWebMapper->fromIdToDeleteCommand($id));

        if ($isCurrentUser) {
            unset($_SESSION['auth']);
            Flash::setSuccess('Tu usuario fue eliminado y la sesion se cerro automaticamente.');
            View::redirect('home');
        }

        Flash::setSuccess('Usuario eliminado correctamente.');
        View::redirect('users.index');
    }

    public function login(): void
    {
        View::render('auth/login', $this->baseFormViewData('Iniciar sesión'));
    }

    public function authenticate(LoginWebRequest $request): void
    {
        $user = $this->loginUseCase->execute($this->userWebMapper->fromLoginRequestToCommand($request));

        $_SESSION['auth'] = [
            'id' => $user->id()->value(),
            'name' => $user->name()->value(),
            'email' => $user->email()->value(),
            'role' => $user->role()->value,
        ];

        Flash::setSuccess('Sesion iniciada correctamente.');
        View::redirect('users.index');
    }

    public function logout(): void
    {
        if (isset($_SESSION['auth'])) {
            unset($_SESSION['auth']);
        }

        Flash::setSuccess('Sesion cerrada correctamente.');
        View::redirect('home');
    }

    public function forgot(): void
    {
        View::render('auth/forgot-password', $this->baseFormViewData('Recuperar contraseña'));
    }

    private function getRequiredUser(string $id): UserModel
    {
        $user = $this->getUserByIdPort->getById(new UserId($id));

        if ($user === null) {
            throw UserNotFoundException::byId($id);
        }

        return $user;
    }

    private function baseViewData(string $pageTitle): array
    {
        return [
            'pageTitle' => $pageTitle,
            'message' => Flash::message(),
            'success' => Flash::success(),
        ];
    }

    private function baseFormViewData(string $pageTitle): array
    {
        return array_merge($this->baseViewData($pageTitle), [
            'errors' => Flash::errors(),
            'old' => Flash::old(),
        ]);
    }

    private function roleOptions(): array
    {
        $options = [];

        foreach (UserRoleEnum::cases() as $case) {
            $options[] = $case->value;
        }

        return $options;
    }

    private function statusOptions(): array
    {
        $options = [];

        foreach (UserStatusEnum::cases() as $case) {
            $options[] = $case->value;
        }

        return $options;
    }

    private function isAdminSession(): bool
    {
        return isset($_SESSION['auth']['role']) && (string) $_SESSION['auth']['role'] === UserRoleEnum::ADMIN->value;
    }
}
