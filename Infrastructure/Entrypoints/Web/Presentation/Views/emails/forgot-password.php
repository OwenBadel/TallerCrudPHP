<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperacion de contraseña</title>
</head>
<body style="font-family:Arial,Helvetica,sans-serif;color:#1f2937;background:#f9fafb;padding:24px;">
    <div style="max-width:640px;margin:0 auto;background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:24px;">
        <h1 style="margin-top:0;">Recuperacion de contraseña</h1>
        <p>Hola <?= htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8') ?>,</p>
        <p>Tu nueva contraseña temporal es:</p>
        <p style="font-size:20px;font-weight:bold;letter-spacing:1px;">&nbsp;<?= htmlspecialchars((string) $tempPassword, ENT_QUOTES, 'UTF-8') ?>&nbsp;</p>
        <p>Usa este correo: <?= htmlspecialchars((string) $email, ENT_QUOTES, 'UTF-8') ?></p>
        <p>Luego ingresa al sistema y cambia la contraseña inmediatamente.</p>
    </div>
</body>
</html>
