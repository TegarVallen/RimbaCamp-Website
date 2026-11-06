<?php
/**
 * View: Login Admin
 * Data dari Controller: $error
 */
?>
<div class="form-box">
    <h2>Login Admin</h2>
    <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
    <form method="post" action="<?= e(url('admin/login')) ?>">
        <label>Username <input type="text" name="user" required></label>
        <label>Password <input type="password" name="pass" required></label>
        <button class="btn" type="submit">Masuk</button>
        <p class="hint">Demo: admin / admin123</p>
    </form>
</div>
