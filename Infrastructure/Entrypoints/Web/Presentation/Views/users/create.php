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
    <form action="?route=users.store" method="post" class="form-stack">
        <div class="form-grid">
            <div class="field">
                <label for="id">ID</label>
                <input id="id" type="text" name="id" value="<?= htmlspecialchars((string) ($old['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="field">
                <label for="name">Nombre</label>
                <input id="name" type="text" name="name" value="<?= htmlspecialchars((string) ($old['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="<?= htmlspecialchars((string) ($old['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="field">
                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password" required>
            </div>
            <div class="field field--full">
                <label for="role">Rol</label>
                <select id="role" name="role">
                    <?php foreach (($roleOptions ?? []) as $option): ?>
                        <option value="<?= htmlspecialchars((string) $option, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) $option, ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-actions">
            <a class="btn btn--secondary" href="?route=home">Cancelar</a>
            <button class="btn" type="submit">Guardar</button>
        </div>
    </form>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
