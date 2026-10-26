<?php if (!empty($_SESSION['flash'])):
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> mt-3" role="alert">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif ?>
