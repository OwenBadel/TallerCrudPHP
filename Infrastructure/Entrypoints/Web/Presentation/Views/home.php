<?php require __DIR__ . '/layouts/header.php'; ?>
<?php require __DIR__ . '/layouts/menu.php'; ?>

<section class="panel">
    <h1><?= htmlspecialchars($pageTitle ?? 'Menu CRUD de Usuarios', ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success"><?= htmlspecialchars((string) $success, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <p class="muted">Selecciona una opcion para navegar por el CRUD.</p>

    <div class="grid" style="margin-top:16px;">
        <div class="panel" style="background:#f8fafc;">
            <ol style="margin:0; padding-left:20px; line-height:1.9;">
                <li><a href="?route=users.index">Listar usuarios</a></li>
                <li>
                    Encontrar usuarios por ID
                    <form action="" method="get" style="margin-top:10px;">
                        <input type="hidden" name="route" value="users.show">
                        <input type="text" name="id" placeholder="Ingresa el ID del usuario" required>
                        <div style="margin-top:10px;">
                            <button type="submit">Buscar por ID</button>
                        </div>
                    </form>
                </li>
                <li><a href="?route=users.create">Crear usuarios</a></li>
                <li><a href="?route=users.index">Actualizar usuario</a></li>
                <li><a href="?route=users.index">Borrar usuario</a></li>
                <li><a href="?route=auth.login">Login</a></li>
                <li><a href="?route=auth.logout">Exit</a></li>
            </ol>
        </div>

        <div class="panel" style="background:#f8fafc;">
            <h2 style="margin-top:0;">Resumen</h2>
            <ul>
                <li>Arquitectura hexagonal</li>
                <li>DDD puro en dominio</li>
                <li>PDO con sentencias preparadas</li>
                <li>Flujo PRG con Flash</li>
            </ul>
        </div>
    </div>
</section>

<?php require __DIR__ . '/layouts/footer.php'; ?>
