<?php
/**
 * View: Katalog Alat
 * Data dari Controller: $rows, $categories, $keyword, $category, $start, $end, $checkDates, $error
 */
?>
<section class="hero">
    <h1>Sewa Alat Camping, Mudah &amp; Cepat</h1>
    <p>Lihat katalog, cek ketersediaan, dan pesan online tanpa ribet.</p>
</section>

<form class="filter" method="get" action="index.php">
    <input type="hidden" name="r" value="equipment/index">
    <input type="text" name="q" placeholder="Cari alat (tenda, carrier, kompor...)" value="<?= e($keyword) ?>">
    <select name="kategori">
        <option value="">Semua Kategori</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= e($cat) ?>" <?= $cat === $category ? 'selected' : '' ?>><?= e($cat) ?></option>
        <?php endforeach; ?>
    </select>
    <label>Mulai <input type="date" name="tgl_mulai" value="<?= e($start) ?>"></label>
    <label>Selesai <input type="date" name="tgl_selesai" value="<?= e($end) ?>"></label>
    <button type="submit" class="btn">Cari / Cek Ketersediaan</button>
</form>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php if ($checkDates): ?>
    <div class="alert alert-info">Ketersediaan untuk <strong><?= e($start) ?></strong> s/d <strong><?= e($end) ?></strong></div>
<?php endif; ?>

<?php if (empty($rows)): ?>
    <p class="empty">Alat tidak ditemukan.</p>
<?php else: ?>
<div class="grid">
    <?php foreach ($rows as $row):
        $item = $row['item'];
        $available = $row['available'];
        $params = ['id' => $item->getId()];
        if ($checkDates) {
            $params['tgl_mulai'] = $start;
            $params['tgl_selesai'] = $end;
        }
    ?>
    <article class="card">
        <div class="card-icon"><?= e($item->getIcon()) ?></div>
        <span class="tag"><?= e($item->getCategory()) ?></span>
        <h3><?= e($item->getName()) ?></h3>
        <p class="desc"><?= e($item->getDescription()) ?></p>
        <p class="price"><?= e($item->getFormattedPrice()) ?></p>
        <p class="stock <?= $available > 0 ? 'ok' : 'out' ?>">
            <?= $available > 0 ? 'Tersedia: ' . $available . ' unit' : 'Stok habis' ?>
        </p>
        <?php if ($available > 0): ?>
            <a class="btn" href="<?= e(url('equipment/show', $params)) ?>">Pesan Sekarang</a>
        <?php else: ?>
            <span class="btn btn-disabled">Tidak Tersedia</span>
        <?php endif; ?>
    </article>
    <?php endforeach; ?>
</div>
<?php endif; ?>
