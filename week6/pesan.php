<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Pemesanan';

$item = $repo->find((int) ($_GET['id'] ?? $_POST['id'] ?? 0));
if (!$item) {
    header('Location: index.php');
    exit;
}

$errors = [];
$name  = trim($_POST['nama'] ?? '');
$phone = trim($_POST['telp'] ?? '');
$qty   = (int) ($_POST['jumlah'] ?? 1);
$start = $_POST['tgl_mulai'] ?? ($_GET['tgl_mulai'] ?? date('Y-m-d'));
$end   = $_POST['tgl_selesai'] ?? ($_GET['tgl_selesai'] ?? date('Y-m-d', strtotime('+1 day')));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($name === '') $errors[] = 'Nama wajib diisi.';
    if (!preg_match('/^[0-9]{9,15}$/', $phone)) $errors[] = 'Nomor HP harus 9-15 digit angka.';
    if ($qty < 1) $errors[] = 'Jumlah minimal 1.';
    if ($start < date('Y-m-d')) $errors[] = 'Tanggal mulai tidak boleh sebelum hari ini.';
    if ($end < $start) $errors[] = 'Tanggal selesai harus setelah tanggal mulai.';

    if (!$errors) {
        $available = $orders->availableQty($item, $start, $end);
        if ($qty > $available) {
            $errors[] = "Stok tidak cukup. Tersedia hanya $available unit pada tanggal tersebut.";
        }
    }

    if (!$errors) {
        $orders->create($item, $name, $phone, $qty, $start, $end);
        header('Location: riwayat.php?telp=' . urlencode($phone) . '&sukses=1');
        exit;
    }
}

$days = max(1, (int) ((strtotime($end) - strtotime($start)) / 86400) + 1);
include __DIR__ . '/includes/header.php';
?>
<div class="form-box">
    <h2>Pesan: <?= e($item->getIcon() . ' ' . $item->getName()) ?></h2>
    <p class="price"><?= e($item->getFormattedPrice()) ?> &middot; Stok total <?= $item->getStock() ?> unit</p>

    <?php foreach ($errors as $err): ?>
        <div class="alert alert-danger"><?= e($err) ?></div>
    <?php endforeach; ?>

    <form method="post">
        <input type="hidden" name="id" value="<?= $item->getId() ?>">
        <label>Nama Lengkap <input type="text" name="nama" value="<?= e($name) ?>" required></label>
        <label>Nomor HP <input type="text" name="telp" value="<?= e($phone) ?>" placeholder="08123456789" required></label>
        <label>Jumlah <input type="number" name="jumlah" min="1" value="<?= $qty ?>" required></label>
        <label>Tanggal Mulai <input type="date" name="tgl_mulai" value="<?= e($start) ?>" required></label>
        <label>Tanggal Selesai <input type="date" name="tgl_selesai" value="<?= e($end) ?>" required></label>
        <p class="estimate">Estimasi (<?= $days ?> hari): <strong>Rp <?= number_format($item->calculateTotal(max(1, $qty), $days), 0, ',', '.') ?></strong></p>
        <button class="btn" type="submit">Kirim Pesanan</button>
        <a class="btn btn-outline" href="index.php">Batal</a>
    </form>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
