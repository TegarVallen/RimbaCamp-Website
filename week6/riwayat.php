<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Riwayat Pesanan';

$phone = trim($_GET['telp'] ?? '');
$list  = $phone !== '' ? $orders->byPhone($phone) : [];

include __DIR__ . '/includes/header.php';
?>
<h2>Riwayat Pesanan</h2>
<?php if (isset($_GET['sukses'])): ?>
    <div class="alert alert-success">Pesanan berhasil dikirim! Status awal: Menunggu persetujuan admin.</div>
<?php endif; ?>

<form class="filter" method="get">
    <input type="text" name="telp" placeholder="Masukkan nomor HP saat memesan" value="<?= e($phone) ?>" required>
    <button class="btn" type="submit">Lihat Riwayat</button>
</form>

<?php if ($phone !== '' && empty($list)): ?>
    <p class="empty">Belum ada pesanan untuk nomor ini.</p>
<?php elseif ($list): ?>
<div class="table-wrap">
<table>
    <thead><tr><th>#</th><th>Alat</th><th>Jumlah</th><th>Tanggal Sewa</th><th>Total</th><th>Status</th></tr></thead>
    <tbody>
    <?php foreach ($list as $o): ?>
        <tr>
            <td><?= $o->getId() ?></td>
            <td><?= e($o->getEquipmentName()) ?></td>
            <td><?= $o->getQty() ?></td>
            <td><?= e($o->getStartDate()) ?> s/d <?= e($o->getEndDate()) ?></td>
            <td><?= e($o->getFormattedTotal()) ?></td>
            <td><span class="badge <?= $o->getStatusClass() ?>"><?= e($o->getStatus()) ?></span></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
