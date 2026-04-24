<?php

final class EmailNotificationService
{
    private string $lastError = '';

    public function sendRecoveryCode(string $email, string $name, string $code): bool
    {
        $htmlBody = $this->renderRecoveryCodeTemplate($email, $name, $code);
        $subject = '=?UTF-8?B?' . base64_encode('Codigo de recuperacion') . '?=';

        $sent = $this->sendHtmlEmail($email, $subject, $htmlBody);

        if (!$sent) {
            $this->logFailure($email, $this->lastError !== '' ? $this->lastError : 'No se pudo entregar el codigo de recuperacion por SMTP.');
        }

        return $sent;
    }

    public function sendWelcome(string $email, string $name, string $tempPassword, string $role): bool
    {
        $htmlBody = $this->renderWelcomeTemplate($email, $name, $tempPassword, $role);
        $subject = '=?UTF-8?B?' . base64_encode('Bienvenido al sistema') . '?=';

        $sent = $this->sendHtmlEmail($email, $subject, $htmlBody);

        if (!$sent) {
            $this->logFailure($email, $this->lastError !== '' ? $this->lastError : 'No se pudo entregar el correo de bienvenida por SMTP.');
        }

        return $sent;
    }

    public function sendPasswordRecovery(string $email, string $name, string $tempPassword): bool
    {
        $htmlBody = $this->renderPasswordRecoveryTemplate($email, $name, $tempPassword);
        $subject = '=?UTF-8?B?' . base64_encode('Recuperacion de contrasena') . '?=';

        $sent = $this->sendHtmlEmail($email, $subject, $htmlBody);

        if (!$sent) {
            $this->logFailure($email, $this->lastError !== '' ? $this->lastError : 'No se pudo entregar el correo por SMTP.');
        }

        return $sent;
    }

    public function lastError(): string
    {
        return $this->lastError;
    }

    private function renderWelcomeTemplate(string $email, string $name, string $tempPassword, string $role): string
    {
        $templateFile = __DIR__ . '/../Entrypoints/Web/Presentation/Views/emails/welcome.php';

        ob_start();
        extract([
            'email' => $email,
            'name' => $name,
            'tempPassword' => $tempPassword,
            'role' => $role,
        ], EXTR_SKIP);
        require $templateFile;

        return (string) ob_get_clean();
    }

    private function renderRecoveryCodeTemplate(string $email, string $name, string $code): string
    {
        $templateFile = __DIR__ . '/../Entrypoints/Web/Presentation/Views/emails/recovery-code.php';

        ob_start();
        extract([
            'email' => $email,
            'name' => $name,
            'code' => $code,
        ], EXTR_SKIP);
        require $templateFile;

        return (string) ob_get_clean();
    }

    private function renderPasswordRecoveryTemplate(string $email, string $name, string $tempPassword): string
    {
        $templateFile = __DIR__ . '/../Entrypoints/Web/Presentation/Views/emails/forgot-password.php';

        ob_start();
        extract([
            'email' => $email,
            'name' => $name,
            'tempPassword' => $tempPassword,
        ], EXTR_SKIP);
        require $templateFile;

        return (string) ob_get_clean();
    }

    private function sendHtmlEmail(string $to, string $subject, string $htmlBody): bool
    {
        $this->lastError = '';

        if (SMTP_HOST === '' || SMTP_PORT <= 0 || SMTP_USERNAME === '' || SMTP_PASSWORD === '') {
            $this->lastError = 'Faltan credenciales SMTP o parametros de conexion.';

            return false;
        }

        $socket = @stream_socket_client(
            'tcp://' . SMTP_HOST . ':' . SMTP_PORT,
            $errorNumber,
            $errorMessage,
            20
        );

        if (!is_resource($socket)) {
            $this->lastError = sprintf('No se pudo abrir la conexion SMTP (%s): %s', (string) $errorNumber, (string) $errorMessage);

            return false;
        }

        stream_set_timeout($socket, 20);

        if (!$this->smtpExpect($socket, [220])) {
            $this->closeSocket($socket);
            $this->lastError = 'El servidor SMTP no respondio con 220 al iniciar.';

            return false;
        }

        if (!$this->smtpCommand($socket, 'EHLO localhost', [250])) {
            $this->closeSocket($socket);
            $this->lastError = 'El servidor SMTP no acepto EHLO.';

            return false;
        }

        if (SMTP_PORT === 587) {
            if (!$this->smtpCommand($socket, 'STARTTLS', [220])) {
                $this->closeSocket($socket);
                $this->lastError = 'El servidor SMTP no acepto STARTTLS.';

                return false;
            }

            if (@stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT) !== true) {
                $this->closeSocket($socket);
                $this->lastError = 'No fue posible negociar TLS con el servidor SMTP.';

                return false;
            }

            if (!$this->smtpCommand($socket, 'EHLO localhost', [250])) {
                $this->closeSocket($socket);
                $this->lastError = 'El servidor SMTP no acepto EHLO despues de TLS.';

                return false;
            }
        }

        if (!$this->smtpCommand($socket, 'AUTH LOGIN', [334])) {
            $this->closeSocket($socket);
            $this->lastError = 'El servidor SMTP no acepto AUTH LOGIN.';

            return false;
        }

        if (!$this->smtpCommand($socket, base64_encode(SMTP_USERNAME), [334])) {
            $this->closeSocket($socket);
            $this->lastError = 'El servidor SMTP rechazo el usuario.';

            return false;
        }

        if (!$this->smtpCommand($socket, base64_encode(SMTP_PASSWORD), [235])) {
            $this->closeSocket($socket);
            $this->lastError = 'El servidor SMTP rechazo la contrasena.';

            return false;
        }

        if (!$this->smtpCommand($socket, 'MAIL FROM:<' . SMTP_FROM_ADDRESS . '>', [250])) {
            $this->closeSocket($socket);
            $this->lastError = 'El servidor SMTP rechazo el remitente.';

            return false;
        }

        if (!$this->smtpCommand($socket, 'RCPT TO:<' . $to . '>', [250, 251])) {
            $this->closeSocket($socket);
            $this->lastError = 'El servidor SMTP rechazo el destinatario.';

            return false;
        }

        if (!$this->smtpCommand($socket, 'DATA', [354])) {
            $this->closeSocket($socket);
            $this->lastError = 'El servidor SMTP no entro en modo DATA.';

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

        $accepted = $this->smtpExpect($socket, [250]);
        $this->smtpCommand($socket, 'QUIT', [221]);
        $this->closeSocket($socket);

        if (!$accepted) {
            $this->lastError = 'El servidor SMTP no acepto el contenido final del mensaje.';
        }

        return $accepted;
    }

    private function smtpCommand($socket, string $command, array $expectedCodes): bool
    {
        fwrite($socket, $command . "\r\n");

        return $this->smtpExpect($socket, $expectedCodes);
    }

    private function smtpExpect($socket, array $expectedCodes): bool
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
            $this->lastError = 'Respuesta vacia del servidor SMTP.';

            return false;
        }

        $code = (int) substr($response, 0, 3);

        return in_array($code, $expectedCodes, true);
    }

    private function closeSocket($socket): void
    {
        if (is_resource($socket)) {
            fclose($socket);
        }
    }

    private function logFailure(string $email, string $error): void
    {
        $directory = dirname(__DIR__, 2) . '/storage';
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $logFile = $directory . '/mail.log';
        $line = sprintf(
            "[%s] email=%s error=%s\n",
            date('Y-m-d H:i:s'),
            $email,
            $error
        );

        file_put_contents($logFile, $line, FILE_APPEND);
    }
}
