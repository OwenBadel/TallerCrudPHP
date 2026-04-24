<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/menu.php'; ?>

<section class="panel">
    <h1><?= htmlspecialchars($pageTitle ?? 'Detalle de usuario', ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success"><?= htmlspecialchars((string) $success, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php $userArray = $user instanceof UserResponse ? $user->toArray() : (array) $user; ?>
    <div class="grid">
        <div><strong>ID</strong><p><?= htmlspecialchars((string) $userArray['id'], ENT_QUOTES, 'UTF-8') ?></p></div>
        <div><strong>Nombre</strong><p><?= htmlspecialchars((string) $userArray['name'], ENT_QUOTES, 'UTF-8') ?></p></div>
        <div><strong>Email</strong><p><?= htmlspecialchars((string) $userArray['email'], ENT_QUOTES, 'UTF-8') ?></p></div>
        <div><strong>Rol</strong><p><?= htmlspecialchars((string) $userArray['role'], ENT_QUOTES, 'UTF-8') ?></p></div>
        <div><strong>Estado</strong><p><?= htmlspecialchars((string) $userArray['status'], ENT_QUOTES, 'UTF-8') ?></p></div>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
