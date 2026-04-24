<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Codigo de recuperacion</title>
</head>
<body style="font-family:Arial,Helvetica,sans-serif;color:#1f2937;background:#f9fafb;padding:24px;">
    <div style="max-width:640px;margin:0 auto;background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:24px;">
        <p>Hola, <?= htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8') ?></p>
        <p>Usa este codigo de 6 digitos para continuar con la recuperacion:</p>
        <p style="font-size:28px;font-weight:bold;letter-spacing:4px;"><?= htmlspecialchars((string) $code, ENT_QUOTES, 'UTF-8') ?></p>
        <p>Email: <?= htmlspecialchars((string) $email, ENT_QUOTES, 'UTF-8') ?></p>
        <p>Este codigo expirara pronto. Si no lo solicitaste, ignora este mensaje.</p>
    </div>
</body>
</html>
