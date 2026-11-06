<?php
/**
 * View: Dashboard Admin
 * Data dari Controller: $stats, $orderList, $stock
 */
$statuses = Order::allStatuses();
?>
<div class="admin-head">
    <h2>Dashboard Admin</h2>
    <a class="btn btn-outline" href="<?= e(url('admin/logout')) ?>">Logout</a>
</div>

<div class="stats">
    <div class="stat"><span><?= $stats['total'] ?></span>Total Pesanan</div>
    <div class="stat"><span><?= $stats['pending'] ?></span>Menunggu</div>
    <div class="stat"><span><?= $stats['approved'] ?></span>Disetujui</div>
    <div class="stat"><span><?= e(rupiah($stats['revenue'])) ?></span>Pendapatan</div>
</div>

<h3>Daftar Pesanan</h3>
<div class="table-wrap">
<table>
    <thead><tr><th>#</th><th>Pelanggan</th><th>Alat</th><th>Qty</th><th>Tanggal</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach ($orderList as $o): ?>
        <tr>
            <td><?= $o->getId() ?></td>
            <td><?= e($o->getCustomerName()) ?><br><small><?= e($o->getPhone()) ?></small></td>
            <td><?= e($o->getEquipmentName()) ?></td>
            <td><?= $o->getQty() ?></td>
            <td><?= e($o->getStartDate()) ?> s/d <?= e($o->getEndDate()) ?></td>
            <td><?= e($o->getFormattedTotal()) ?></td>
            <td><span class="badge <?= $o->getStatusClass() ?>"><?= e($o->getStatus()) ?></span></td>
            <td>
                <form method="post" class="inline" action="<?= e(url('admin/process')) ?>">
                    <input type="hidden" name="order_id" value="<?= $o->getId() ?>">
                    <select name="status">
                        <?php foreach ($statuses as $s): ?>
                            <option <?= $s === $o->getStatus() ? 'selected' : '' ?>><?= e($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-small" type="submit">Simpan</button>
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
    <?php foreach ($stock as $row): $item = $row['item']; ?>
        <tr>
            <td><?= e($item->getIcon() . ' ' . $item->getName()) ?></td>
            <td><?= e($item->getCategory()) ?></td>
            <td><?= e($item->getFormattedPrice()) ?></td>
            <td><?= $item->getStock() ?></td>
            <td><?= $row['booked'] ?></td>
            <td><?= $row['available'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
