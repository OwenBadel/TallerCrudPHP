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
        case 'auth.forgot.code':
            handleForgotCodePage($container);
            break;
        case 'auth.forgot.code.check':
            handleForgotCodeCheck($container);
            break;
        case 'auth.forgot.reset':
            handleForgotResetPage($container);
            break;
        case 'auth.forgot.reset.submit':
            handleForgotResetSubmit($container);
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
    $recoveryService = $container->getPasswordRecoveryService();

    if ($email === '') {
        Flash::setMessage('Ingresa un correo valido.');
        View::redirect('auth.forgot');
    }

    try {
        if (!$recoveryService->requestCode($email)) {
            Flash::setMessage($recoveryService->lastError() !== '' ? $recoveryService->lastError() : 'No fue posible iniciar la recuperacion.');
            View::redirect('auth.forgot');
        }

        Flash::setSuccess('Te enviamos un codigo de 6 digitos a tu correo.');
        View::redirect('auth.forgot.code');
    } catch (Throwable $exception) {
        Flash::setMessage($exception->getMessage());
        View::redirect('auth.forgot');
    }
}

function handleForgotCodePage(DependencyInjection $container): void
{
    $recoveryService = $container->getPasswordRecoveryService();

    if (!$recoveryService->hasPendingChallenge()) {
        Flash::setMessage('Primero debes solicitar el codigo de recuperacion.');
        View::redirect('auth.forgot');
    }

    View::render('auth/forgot-code', [
        'pageTitle' => 'Verificar codigo',
        'message' => Flash::message(),
        'success' => Flash::success(),
        'email' => $recoveryService->challengeEmail(),
    ]);
}

function handleForgotCodeCheck(DependencyInjection $container): void
{
    $code = trim((string) ($_POST['code'] ?? ''));
    $recoveryService = $container->getPasswordRecoveryService();

    if ($code === '') {
        Flash::setMessage('Ingresa el codigo de 6 digitos.');
        View::redirect('auth.forgot.code');
    }

    if (!$recoveryService->verifyCode($code)) {
        Flash::setMessage($recoveryService->lastError() !== '' ? $recoveryService->lastError() : 'El codigo no es valido.');
        View::redirect('auth.forgot.code');
    }

    Flash::setSuccess('Codigo verificado. Ahora puedes crear tu nueva contraseña.');
    View::redirect('auth.forgot.reset');
}

function handleForgotResetPage(DependencyInjection $container): void
{
    $recoveryService = $container->getPasswordRecoveryService();

    if (!$recoveryService->hasPendingChallenge()) {
        Flash::setMessage('Primero debes solicitar y verificar el codigo de recuperacion.');
        View::redirect('auth.forgot');
    }

    if (empty($_SESSION['password_recovery']['verified'])) {
        Flash::setMessage('Primero debes verificar el codigo de recuperacion.');
        View::redirect('auth.forgot.code');
    }

    View::render('auth/forgot-reset', [
        'pageTitle' => 'Nueva contraseña',
        'message' => Flash::message(),
        'success' => Flash::success(),
        'email' => $recoveryService->challengeEmail(),
    ]);
}

function handleForgotResetSubmit(DependencyInjection $container): void
{
    $password = (string) ($_POST['password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');
    $recoveryService = $container->getPasswordRecoveryService();

    if (!$recoveryService->hasPendingChallenge() || empty($_SESSION['password_recovery']['verified'])) {
        Flash::setMessage('Primero debes verificar el codigo de recuperacion.');
        View::redirect('auth.forgot');
    }

    if ($password === '' || $confirmPassword === '') {
        Flash::setMessage('Completa ambos campos de contraseña.');
        View::redirect('auth.forgot.reset');
    }

    if ($password !== $confirmPassword) {
        Flash::setMessage('Las contraseñas no coinciden.');
        View::redirect('auth.forgot.reset');
    }

    try {
        if (!$recoveryService->resetPassword($password)) {
            Flash::setMessage($recoveryService->lastError() !== '' ? $recoveryService->lastError() : 'No fue posible actualizar la contraseña.');
            View::redirect('auth.forgot.reset');
        }

        Flash::setSuccess('Tu contraseña fue actualizada correctamente. Ahora inicia sesión.');
        View::redirect('auth.login');
    } catch (Throwable $exception) {
        Flash::setMessage($exception->getMessage());
        View::redirect('auth.forgot.reset');
    }
}
