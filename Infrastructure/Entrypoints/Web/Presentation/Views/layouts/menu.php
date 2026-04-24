<?php

$auth = $_SESSION['auth'] ?? null;
?>
<div class="brand">
    <div>
        <strong>CRUD Usuarios</strong>
        <span class="muted">Arquitectura Hexagonal + DDD</span>
    </div>
    <nav class="actions">
        <a href="?route=home">Inicio</a>
        <?php if ($auth !== null): ?>
            <a href="?route=users.index">Usuarios</a>
            <a href="?route=users.create">Nuevo usuario</a>
            <span class="muted">Hola, <?= htmlspecialchars((string) $auth['name'], ENT_QUOTES, 'UTF-8') ?></span>
            <a href="?route=auth.logout">Cerrar sesión</a>
        <?php else: ?>
            <a href="?route=auth.login">Iniciar sesión</a>
            <a href="?route=auth.forgot">Recuperar contraseña</a>
        <?php endif; ?>
    </nav>
</div>
