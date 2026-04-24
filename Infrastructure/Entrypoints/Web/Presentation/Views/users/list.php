<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/menu.php'; ?>

<?php $currentUserId = $_SESSION['auth']['id'] ?? null; ?>

<section class="panel">
    <h1><?= htmlspecialchars($pageTitle ?? 'Usuarios', ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success"><?= htmlspecialchars((string) $success, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <div class="actions" style="margin-bottom:16px; justify-content:flex-end;">
        <a class="btn" href="?route=users.create">Crear usuario</a>
    </div>

    <table>
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($users)): ?>
            <tr>
                <td colspan="5" class="muted">No hay usuarios registrados.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($users as $user): ?>
                <?php $userArray = $user instanceof UserResponse ? $user->toArray() : (array) $user; ?>
                <?php $isCurrentUser = $currentUserId !== null && (string) $currentUserId === (string) $userArray['id']; ?>
                <tr>
                    <td><?= htmlspecialchars((string) $userArray['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $userArray['email'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $userArray['role'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $userArray['status'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <div class="actions">
                            <a href="?route=users.show&id=<?= urlencode((string) $userArray['id']) ?>">Ver</a>
                            <a class="btn btn--secondary" href="?route=users.edit&id=<?= urlencode((string) $userArray['id']) ?>">Editar</a>
                            <form action="?route=users.delete" method="post" style="display:inline; margin:0;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars((string) $userArray['id'], ENT_QUOTES, 'UTF-8') ?>">
                                <button
                                    class="btn btn--danger"
                                    type="submit"
                                    onclick="return <?= $isCurrentUser ? "confirm('Estas eliminando tu propia cuenta. La sesion se cerrara de inmediato. ¿Continuar?') && prompt('Escribe ELIMINAR para confirmar') === 'ELIMINAR'" : "confirm('¿Eliminar este usuario?')" ?>;"
                                >
                                    <?= $isCurrentUser ? 'Eliminar mi cuenta' : 'Eliminar' ?>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
