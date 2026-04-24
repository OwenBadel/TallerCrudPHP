<?php

final class PasswordRecoveryService
{
    private UserRepositoryMySQL $userRepository;
    private EmailNotificationService $emailNotificationService;
    private string $lastError = '';

    public function __construct(UserRepositoryMySQL $userRepository, EmailNotificationService $emailNotificationService)
    {
        $this->userRepository = $userRepository;
        $this->emailNotificationService = $emailNotificationService;
    }

    public function requestCode(string $email): bool
    {
        $this->lastError = '';
        $email = trim(strtolower($email));

        $user = $this->userRepository->getByEmail(new UserEmail($email));
        if ($user === null || $user->status() !== UserStatusEnum::ACTIVE) {
            $this->lastError = 'No fue posible iniciar la recuperacion.';

            return false;
        }

        $code = (string) random_int(100000, 999999);
        $_SESSION['password_recovery'] = [
            'email' => $user->email()->value(),
            'name' => $user->name()->value(),
            'code_hash' => password_hash($code, PASSWORD_DEFAULT),
            'expires_at' => time() + 900,
            'verified' => false,
        ];

        if (!$this->emailNotificationService->sendRecoveryCode($user->email()->value(), $user->name()->value(), $code)) {
            $this->lastError = $this->emailNotificationService->lastError();
            unset($_SESSION['password_recovery']);

            return false;
        }

        return true;
    }

    public function verifyCode(string $code): bool
    {
        $this->lastError = '';

        if (!$this->hasValidChallenge()) {
            $this->lastError = 'No hay una solicitud de recuperacion activa.';

            return false;
        }

        if (!password_verify($code, (string) $_SESSION['password_recovery']['code_hash'])) {
            $this->lastError = 'El codigo ingresado no es valido.';

            return false;
        }

        $_SESSION['password_recovery']['verified'] = true;

        return true;
    }

    public function resetPassword(string $newPassword): bool
    {
        $this->lastError = '';

        if (!$this->hasValidChallenge() || empty($_SESSION['password_recovery']['verified'])) {
            $this->lastError = 'Primero debes verificar el codigo.';

            return false;
        }

        $email = (string) $_SESSION['password_recovery']['email'];
        $user = $this->userRepository->getByEmail(new UserEmail($email));

        if ($user === null) {
            $this->lastError = 'No se encontro el usuario para actualizar la contraseña.';

            return false;
        }

        $updatedUser = $user->changePassword(UserPassword::fromPlainText($newPassword));
        $this->userRepository->update($updatedUser);
        unset($_SESSION['password_recovery']);

        return true;
    }

    public function hasPendingChallenge(): bool
    {
        return $this->hasValidChallenge();
    }

    public function challengeEmail(): ?string
    {
        if (!$this->hasValidChallenge()) {
            return null;
        }

        return (string) $_SESSION['password_recovery']['email'];
    }

    public function lastError(): string
    {
        return $this->lastError;
    }

    public function clear(): void
    {
        unset($_SESSION['password_recovery']);
        $this->lastError = '';
    }

    private function hasValidChallenge(): bool
    {
        if (empty($_SESSION['password_recovery'])) {
            return false;
        }

        $challenge = $_SESSION['password_recovery'];

        if (!isset($challenge['expires_at'], $challenge['email'], $challenge['code_hash'])) {
            return false;
        }

        if ((int) $challenge['expires_at'] < time()) {
            unset($_SESSION['password_recovery']);

            return false;
        }

        return true;
    }
}
