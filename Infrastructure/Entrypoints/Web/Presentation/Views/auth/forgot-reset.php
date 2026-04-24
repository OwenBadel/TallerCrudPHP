<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/menu.php'; ?>

<section class="panel">
    <h1><?= htmlspecialchars($pageTitle ?? 'Nueva contraseña', ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success"><?= htmlspecialchars((string) $success, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <p class="muted">Cuenta verificada para <?= htmlspecialchars((string) ($email ?? ''), ENT_QUOTES, 'UTF-8') ?>.</p>

    <form action="?route=auth.forgot.reset.submit" method="post" class="form-stack" style="max-width:420px;">
        <div class="field">
            <label for="password">Nueva contraseña</label>
            <input id="password" type="password" name="password" required>
        </div>
        <div class="field">
            <label for="confirm_password">Confirmar contraseña</label>
            <input id="confirm_password" type="password" name="confirm_password" required>
        </div>
        <div class="form-actions" style="justify-content:flex-start;">
            <a class="btn btn--secondary" href="?route=auth.login">Cancelar</a>
            <button class="btn" type="submit">Actualizar contraseña</button>
        </div>
    </form>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
