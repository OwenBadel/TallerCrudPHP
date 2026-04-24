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
            $updatedUser = $user->changePassword(UserPassword::fromPlainText($tempPassword));
            $repository->update($updatedUser);
            sendPasswordRecoveryEmail($user->email()->value(), $user->name()->value(), $tempPassword);
        }

        Flash::setSuccess('Si el correo existe, recibirás instrucciones para recuperar tu contraseña.');
        View::redirect('auth.forgot');
    } catch (Throwable $exception) {
        Flash::setMessage($exception->getMessage());
        View::redirect('auth.forgot');
    }
}

function sendPasswordRecoveryEmail(string $email, string $name, string $tempPassword): void
{
    $templateFile = __DIR__ . '/../Infrastructure/Entrypoints/Web/Presentation/Views/emails/forgot-password.php';

    ob_start();
    extract([
        'email' => $email,
        'name' => $name,
        'tempPassword' => $tempPassword,
    ], EXTR_SKIP);
    require $templateFile;
    $htmlBody = (string) ob_get_clean();

    $subject = '=?UTF-8?B?' . base64_encode('Recuperacion de contrasena') . '?=';
    $headers = implode("\r\n", [
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . SMTP_FROM_NAME . ' <' . SMTP_FROM_ADDRESS . '>',
    ]);

    $sent = sendWithSmtp($email, $subject, $htmlBody);

    if (!$sent && function_exists('mail')) {
        @mail($email, $subject, $htmlBody, $headers);
    }
}

function sendWithSmtp(string $to, string $subject, string $htmlBody): bool
{
    if (SMTP_HOST === '' || SMTP_PORT <= 0 || SMTP_USERNAME === '' || SMTP_PASSWORD === '') {
        return false;
    }

    $socket = @stream_socket_client(
        'tcp://' . SMTP_HOST . ':' . SMTP_PORT,
        $errorNumber,
        $errorMessage,
        20
    );

    if (!is_resource($socket)) {
        return false;
    }

    stream_set_timeout($socket, 20);

    if (!smtpExpect($socket, [220])) {
        fclose($socket);
        return false;
    }

    if (!smtpCommand($socket, 'EHLO localhost', [250])) {
        fclose($socket);
        return false;
    }

    if (SMTP_PORT === 587) {
        if (!smtpCommand($socket, 'STARTTLS', [220])) {
            fclose($socket);
            return false;
        }

        if (@stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT) !== true) {
            fclose($socket);
            return false;
        }

        if (!smtpCommand($socket, 'EHLO localhost', [250])) {
            fclose($socket);
            return false;
        }
    }

    if (!smtpCommand($socket, 'AUTH LOGIN', [334])) {
        fclose($socket);
        return false;
    }

    if (!smtpCommand($socket, base64_encode(SMTP_USERNAME), [334])) {
        fclose($socket);
        return false;
    }

    if (!smtpCommand($socket, base64_encode(SMTP_PASSWORD), [235])) {
        fclose($socket);
        return false;
    }

    if (!smtpCommand($socket, 'MAIL FROM:<' . SMTP_FROM_ADDRESS . '>', [250])) {
        fclose($socket);
        return false;
    }

    if (!smtpCommand($socket, 'RCPT TO:<' . $to . '>', [250, 251])) {
        fclose($socket);
        return false;
    }

    if (!smtpCommand($socket, 'DATA', [354])) {
        fclose($socket);
        return false;
    }

    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . SMTP_FROM_NAME . ' <' . SMTP_FROM_ADDRESS . '>',
        'To: <' . $to . '>',
        'Subject: ' . $subject,
    ];

    $data = implode("\r\n", $headers) . "\r\n\r\n" . $htmlBody . "\r\n.\r\n";
    fwrite($socket, $data);

    $accepted = smtpExpect($socket, [250]);
    smtpCommand($socket, 'QUIT', [221]);
    fclose($socket);

    return $accepted;
}

function smtpCommand($socket, string $command, array $expectedCodes): bool
{
    fwrite($socket, $command . "\r\n");

    return smtpExpect($socket, $expectedCodes);
}

function smtpExpect($socket, array $expectedCodes): bool
{
    $response = '';

    while (!feof($socket)) {
        $line = fgets($socket, 512);
        if ($line === false) {
            break;
        }

        $response .= $line;

        if (strlen($line) >= 4 && $line[3] === ' ') {
            break;
        }
    }

    if (strlen($response) < 3) {
        return false;
    }

    $code = (int) substr($response, 0, 3);

    return in_array($code, $expectedCodes, true);
}
