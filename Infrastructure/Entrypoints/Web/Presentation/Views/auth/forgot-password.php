<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/menu.php'; ?>

<section class="panel">
    <h1><?= htmlspecialchars($pageTitle ?? 'Recuperar contraseña', ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success"><?= htmlspecialchars((string) $success, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error-box">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php $old = is_array($old ?? null) ? $old : []; ?>
    <form action="?route=auth.forgot.send" method="post" style="max-width:420px;">
        <label>Email
            <input type="email" name="email" value="<?= htmlspecialchars((string) ($old['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
        </label>
        <br><br>
        <button type="submit">Enviar instrucciones</button>
    </form>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
