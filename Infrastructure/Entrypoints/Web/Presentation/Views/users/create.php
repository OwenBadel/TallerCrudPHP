<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/menu.php'; ?>

<section class="panel">
    <h1><?= htmlspecialchars($pageTitle ?? 'Crear usuario', ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success"><?= htmlspecialchars((string) $success, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error-box">
            <strong>Corrige los errores del formulario.</strong>
            <ul>
                <?php foreach ($errors as $field => $error): ?>
                    <li><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php $old = is_array($old ?? null) ? $old : []; ?>
    <form action="?route=users.store" method="post">
        <div class="grid">
            <label>ID
                <input type="text" name="id" value="<?= htmlspecialchars((string) ($old['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            </label>
            <label>Nombre
                <input type="text" name="name" value="<?= htmlspecialchars((string) ($old['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
            </label>
            <label>Email
                <input type="email" name="email" value="<?= htmlspecialchars((string) ($old['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
            </label>
            <label>Contraseña
                <input type="password" name="password" required>
            </label>
            <label>Rol
                <select name="role">
                    <?php foreach (($roleOptions ?? []) as $option): ?>
                        <option value="<?= htmlspecialchars((string) $option, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) $option, ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>
        <div style="margin-top:16px;">
            <button type="submit">Guardar</button>
        </div>
    </form>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
