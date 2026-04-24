<?php require __DIR__ . '/layouts/header.php'; ?>
<?php require __DIR__ . '/layouts/menu.php'; ?>

<section class="panel">
    <h1><?= htmlspecialchars($pageTitle ?? 'Inicio', ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success"><?= htmlspecialchars((string) $success, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <p>Aplicacion base en PHP puro sin frameworks, lista para CRUD de usuarios con hexagonal y DDD.</p>
</section>

<?php require __DIR__ . '/layouts/footer.php'; ?>
