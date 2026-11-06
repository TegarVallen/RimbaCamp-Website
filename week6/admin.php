<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Dashboard Admin';

// Login admin sederhana (demo)
const ADMIN_USER = 'admin';
const ADMIN_PASS = 'admin123';

if (isset($_GET['logout'])) {
    unset($_SESSION['admin']);
    header('Location: admin.php');
    exit;
}

$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if (($_POST['user'] ?? '') === ADMIN_USER && ($_POST['pass'] ?? '') === ADMIN_PASS) {
        $_SESSION['admin'] = true;
        header('Location: admin.php');
        exit;
    }
    $loginError = 'Username atau password salah.';
}

$isAdmin = !empty($_SESSION['admin']);

// Ubah status pesanan
if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $orders->updateStatus((int) $_POST['order_id'], $_POST['status'] ?? '');
    header('Location: admin.php');
    exit;
}

include __DIR__ . '/includes/header.php';

if (!$isAdmin): ?>
<div class="form-box">
    <h2>Login Admin</h2>
    <?php if ($loginError): ?><div class="alert alert-danger"><?= e($loginError) ?></div><?php endif; ?>
    <form method="post">
        <label>Username <input type="text" name="user" required></label>
        <label>Password <input type="password" name="pass" required></label>
        <button class="btn" type="submit" name="login" value="1">Masuk</button>
        <p class="hint">Demo: admin / admin123</p>
    </form>
</div>
<?php else: ?>
<div class="admin-head">
    <h2>Dashboard Admin</h2>
    <a class="btn btn-outline" href="admin.php?logout=1">Logout</a>
</div>

<div class="stats">
    <div class="stat"><span><?= $orders->count() ?></span>Total Pesanan</div>
    <div class="stat"><span><?= $orders->countByStatus(Order::STATUS_PENDING) ?></span>Menunggu</div>
    <div class="stat"><span><?= $orders->countByStatus(Order::STATUS_APPROVED) ?></span>Disetujui</div>
    <div class="stat"><span>Rp <?= number_format($orders->totalRevenue(), 0, ',', '.') ?></span>Pendapatan</div>
</div>

<h3>Daftar Pesanan</h3>
<div class="table-wrap">
<table>
    <thead><tr><th>#</th><th>Pelanggan</th><th>Alat</th><th>Qty</th><th>Tanggal</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach ($orders->all() as $o): ?>
        <tr>
            <td><?= $o->getId() ?></td>
            <td><?= e($o->getCustomerName()) ?><br><small><?= e($o->getPhone()) ?></small></td>
            <td><?= e($o->getEquipmentName()) ?></td>
            <td><?= $o->getQty() ?></td>
            <td><?= e($o->getStartDate()) ?> s/d <?= e($o->getEndDate()) ?></td>
            <td><?= e($o->getFormattedTotal()) ?></td>
            <td><span class="badge <?= $o->getStatusClass() ?>"><?= e($o->getStatus()) ?></span></td>
            <td>
                <form method="post" class="inline">
                    <input type="hidden" name="order_id" value="<?= $o->getId() ?>">
                    <select name="status">
                        <?php foreach (Order::allStatuses() as $s): ?>
                            <option <?= $s === $o->getStatus() ? 'selected' : '' ?>><?= e($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-small" name="update_status" value="1">Simpan</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>

<h3>Stok Alat (hari ini)</h3>
<div class="table-wrap">
<table>
    <thead><tr><th>Alat</th><th>Kategori</th><th>Harga</th><th>Stok Total</th><th>Sedang Disewa</th><th>Tersedia</th></tr></thead>
    <tbody>
    <?php $today = date('Y-m-d'); foreach ($repo->all() as $item): ?>
        <tr>
            <td><?= e($item->getIcon() . ' ' . $item->getName()) ?></td>
            <td><?= e($item->getCategory()) ?></td>
            <td><?= e($item->getFormattedPrice()) ?></td>
            <td><?= $item->getStock() ?></td>
            <td><?= $orders->bookedQty($item->getId(), $today, $today) ?></td>
            <td><?= $orders->availableQty($item, $today, $today) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif;
include __DIR__ . '/includes/footer.php';
