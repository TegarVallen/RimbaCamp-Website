<?php
/**
 * View: Form Pemesanan
 * Data dari Controller: $item, $old, $errors, $days, $estimate
 */
?>
<div class="form-box">
    <h2>Pesan: <?= e($item->getIcon() . ' ' . $item->getName()) ?></h2>
    <p class="price"><?= e($item->getFormattedPrice()) ?> &middot; Stok total <?= $item->getStock() ?> unit</p>

    <?php foreach ($errors as $err): ?>
        <div class="alert alert-danger"><?= e($err) ?></div>
    <?php endforeach; ?>

    <form method="post" action="<?= e(url('order/process')) ?>">
        <input type="hidden" name="id" value="<?= $item->getId() ?>">
        <label>Nama Lengkap <input type="text" name="nama" value="<?= e($old['nama']) ?>" required></label>
        <label>Nomor HP <input type="text" name="telp" value="<?= e($old['telp']) ?>" placeholder="08123456789" required></label>
        <label>Jumlah <input type="number" name="jumlah" min="1" value="<?= (int) $old['jumlah'] ?>" required></label>
        <label>Tanggal Mulai <input type="date" name="tgl_mulai" value="<?= e($old['tgl_mulai']) ?>" required></label>
        <label>Tanggal Selesai <input type="date" name="tgl_selesai" value="<?= e($old['tgl_selesai']) ?>" required></label>
        <p class="estimate">Estimasi (<?= $days ?> hari): <strong><?= e(rupiah($estimate)) ?></strong></p>
        <button class="btn" type="submit">Kirim Pesanan</button>
        <a class="btn btn-outline" href="<?= e(url('equipment/index')) ?>">Batal</a>
    </form>
</div>
