<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/smtp.php';
require_once __DIR__ . '/../Common/ClassLoader.php';
require_once __DIR__ . '/../Common/DependencyInjection.php';

DependencyInjection::boot();

$container = new DependencyInjection();
$controller = $container->getUserController();
$routes = WebRoutes::all();
$routeName = $_GET['route'] ?? 'home';
$requestMethod = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

try {
    if (!isset($routes[$routeName])) {
        throw new RuntimeException(sprintf('La ruta "%s" no existe.', $routeName));
    }

    $route = $routes[$routeName];

    if ($route['method'] !== $requestMethod) {
        throw new RuntimeException(sprintf('El metodo HTTP para "%s" no es valido.', $routeName));
    }

    if (($route['public'] ?? false) === false && !isset($_SESSION['auth'])) {
        Flash::setMessage('Debes iniciar sesion para acceder a esta pagina.');
        View::redirect('auth.login');
    }

    switch ($routeName) {
        case 'home':
            $controller->home();
            break;
        case 'users.index':
            $controller->index();
            break;
        case 'users.create':
            $controller->create();
            break;
        case 'users.store':
            $controller->store(buildCreateUserRequest());
            break;
        case 'users.show':
            $controller->show((string) ($_GET['id'] ?? ''));
            break;
        case 'users.edit':
            $controller->edit((string) ($_GET['id'] ?? ''));
            break;
        case 'users.update':
            $controller->update(buildUpdateUserRequest());
            break;
        case 'users.delete':
            $controller->delete((string) ($_POST['id'] ?? ''));
            break;
        case 'auth.login':
            $controller->login();
            break;
        case 'auth.authenticate':
            $controller->authenticate(buildLoginWebRequest());
            break;
        case 'auth.logout':
            $controller->logout();
            break;
        case 'auth.forgot':
            $controller->forgot();
            break;
        case 'auth.forgot.send':
            handleForgotPasswordRequest($container);
            break;
        default:
            throw new RuntimeException(sprintf('La accion para la ruta "%s" no esta implementada.', $routeName));
    }
} catch (Throwable $exception) {
    Flash::setMessage($exception->getMessage());

    View::render('home', [
        'pageTitle' => 'Error',
        'message' => Flash::message(),
        'success' => Flash::success(),
    ]);
}

function buildCreateUserRequest(): CreateUserRequest
{
    return new CreateUserRequest(
        (string) ($_POST['id'] ?? ''),
        (string) ($_POST['name'] ?? ''),
        (string) ($_POST['email'] ?? ''),
        (string) ($_POST['password'] ?? ''),
        (string) ($_POST['role'] ?? '')
    );
}

function buildUpdateUserRequest(): UpdateUserRequest
{
    return new UpdateUserRequest(
        (string) ($_POST['id'] ?? ''),
        (string) ($_POST['name'] ?? ''),
        (string) ($_POST['email'] ?? ''),
        (string) ($_POST['password'] ?? ''),
        (string) ($_POST['role'] ?? ''),
        (string) ($_POST['status'] ?? '')
    );
}

function buildLoginWebRequest(): LoginWebRequest
{
    return new LoginWebRequest(
        (string) ($_POST['email'] ?? ''),
        (string) ($_POST['password'] ?? '')
    );
}

function handleForgotPasswordRequest(DependencyInjection $container): void
{
    $email = trim((string) ($_POST['email'] ?? ''));
    $repository = $container->getUserRepository();

    if ($email === '') {
        Flash::setMessage('Ingresa un correo valido.');
        View::redirect('auth.forgot');
    }

    try {
        $userEmail = new UserEmail($email);
        $user = $repository->getByEmail($userEmail);

        if ($user !== null && $user->status() === UserStatusEnum::ACTIVE) {
            $tempPassword = bin2hex(random_bytes(5));
            $notificationService = $container->getEmailNotificationService();
            if (!$notificationService->sendPasswordRecovery($user->email()->value(), $user->name()->value(), $tempPassword)) {
                Flash::setMessage('No fue posible enviar el correo de recuperacion. Revisa la configuracion SMTP y el log storage/mail.log.');
                View::redirect('auth.forgot');
            }

            $updatedUser = $user->changePassword(UserPassword::fromPlainText($tempPassword));
            $repository->update($updatedUser);
        }

        Flash::setSuccess('Si el correo existe, recibirás instrucciones para recuperar tu contraseña.');
        View::redirect('auth.forgot');
    } catch (Throwable $exception) {
        Flash::setMessage($exception->getMessage());
        View::redirect('auth.forgot');
    }
}
