<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido al sistema</title>
</head>
<body style="font-family:Arial,Helvetica,sans-serif;color:#1f2937;background:#f9fafb;padding:24px;">
    <div style="max-width:640px;margin:0 auto;background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:24px;">
        <p>Hola, <?= htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8') ?></p>
        <p>Tu cuenta ha sido creada exitosamente.</p>
        <p>Email: <?= htmlspecialchars((string) $email, ENT_QUOTES, 'UTF-8') ?></p>
        <p>Contraseña temporal: <?= htmlspecialchars((string) $tempPassword, ENT_QUOTES, 'UTF-8') ?></p>
        <p>Rol: <?= htmlspecialchars((string) $role, ENT_QUOTES, 'UTF-8') ?></p>
        <p>Por favor cambia tu contraseña al ingresar.</p>
    </div>
</body>
</html>
