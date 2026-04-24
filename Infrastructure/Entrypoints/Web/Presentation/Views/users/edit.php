<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/menu.php'; ?>

<?php $old = is_array($old ?? null) ? $old : []; ?>
<?php $userArray = $user instanceof UserResponse ? $user->toArray() : (array) $user; ?>

<section class="panel">
    <h1><?= htmlspecialchars($pageTitle ?? 'Editar usuario', ENT_QUOTES, 'UTF-8') ?></h1>

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

    <form action="?route=users.update" method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars((string) ($old['id'] ?? $userArray['id']), ENT_QUOTES, 'UTF-8') ?>">
        <div class="grid">
            <label>Nombre
                <input type="text" name="name" value="<?= htmlspecialchars((string) ($old['name'] ?? $userArray['name']), ENT_QUOTES, 'UTF-8') ?>" required>
            </label>
            <label>Email
                <input type="email" name="email" value="<?= htmlspecialchars((string) ($old['email'] ?? $userArray['email']), ENT_QUOTES, 'UTF-8') ?>" required>
            </label>
            <label>Contraseña nueva
                <input type="password" name="password" placeholder="Dejar vacio para no cambiarla">
            </label>
            <label>Rol
                <select name="role">
                    <?php foreach (($roleOptions ?? []) as $option): ?>
                        <option value="<?= htmlspecialchars((string) $option, ENT_QUOTES, 'UTF-8') ?>" <?= (($old['role'] ?? $userArray['role']) === $option) ? 'selected' : '' ?>>
                            <?= htmlspecialchars((string) $option, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Estado
                <select name="status">
                    <?php foreach (($statusOptions ?? []) as $option): ?>
                        <option value="<?= htmlspecialchars((string) $option, ENT_QUOTES, 'UTF-8') ?>" <?= (($old['status'] ?? $userArray['status']) === $option) ? 'selected' : '' ?>>
                            <?= htmlspecialchars((string) $option, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>
        <div style="margin-top:16px;">
            <button type="submit">Actualizar</button>
        </div>
    </form>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
