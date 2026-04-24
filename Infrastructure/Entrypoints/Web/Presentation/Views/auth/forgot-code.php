<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/menu.php'; ?>

<section class="panel">
    <h1><?= htmlspecialchars($pageTitle ?? 'Verificar codigo', ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success"><?= htmlspecialchars((string) $success, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <p class="muted">Ingresa el codigo de 6 digitos enviado a <?= htmlspecialchars((string) ($email ?? ''), ENT_QUOTES, 'UTF-8') ?>.</p>

    <form action="?route=auth.forgot.code.check" method="post" class="form-stack" style="max-width:420px;">
        <div class="field">
            <label for="code">Codigo de verificacion</label>
            <input id="code" type="text" name="code" inputmode="numeric" maxlength="6" minlength="6" required>
        </div>
        <div class="form-actions" style="justify-content:flex-start;">
            <a class="btn btn--secondary" href="?route=auth.forgot">Volver</a>
            <button class="btn" type="submit">Verificar codigo</button>
        </div>
    </form>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
