<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/menu.php'; ?>

<?php $old = is_array($old ?? null) ? $old : []; ?>
<?php $userArray = $user instanceof UserResponse ? $user->toArray() : (array) $user; ?>
<?php $canManageSecurity = !empty($canManageSecurity); ?>

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

    <form action="?route=users.update" method="post" class="form-stack">
        <input type="hidden" name="id" value="<?= htmlspecialchars((string) ($old['id'] ?? $userArray['id']), ENT_QUOTES, 'UTF-8') ?>">
        <div class="form-grid">
            <div class="field">
                <label for="name">Nombre</label>
                <input id="name" type="text" name="name" value="<?= htmlspecialchars((string) ($old['name'] ?? $userArray['name']), ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="<?= htmlspecialchars((string) ($old['email'] ?? $userArray['email']), ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <?php if ($canManageSecurity): ?>
                <div class="field">
                    <label for="role">Rol</label>
                    <select id="role" name="role">
                        <?php foreach (($roleOptions ?? []) as $option): ?>
                            <option value="<?= htmlspecialchars((string) $option, ENT_QUOTES, 'UTF-8') ?>" <?= (($old['role'] ?? $userArray['role']) === $option) ? 'selected' : '' ?>>
                                <?= htmlspecialchars((string) $option, ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="password">Contraseña nueva</label>
                    <input id="password" type="password" name="password" placeholder="Dejar vacio para no cambiarla">
                </div>
                <div class="field field--full">
                    <label for="status">Estado</label>
                    <select id="status" name="status">
                        <?php foreach (($statusOptions ?? []) as $option): ?>
                            <option value="<?= htmlspecialchars((string) $option, ENT_QUOTES, 'UTF-8') ?>" <?= (($old['status'] ?? $userArray['status']) === $option) ? 'selected' : '' ?>>
                                <?= htmlspecialchars((string) $option, ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php else: ?>
                <div class="field field--full">
                    <label>Permisos</label>
                    <p class="muted" style="margin:0;">Solo un usuario ADMIN puede cambiar rol, estado y contraseña.</p>
                </div>
            <?php endif; ?>
        </div>
        <div class="form-actions">
            <a class="btn btn--secondary" href="?route=users.index">Cancelar</a>
            <button class="btn" type="submit">Actualizar</button>
        </div>
    </form>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
