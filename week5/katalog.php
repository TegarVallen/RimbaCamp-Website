<?php
// ============================================================
// TASK 03 - Build Dynamic Server-Side Content
// Kartu katalog dibuat otomatis oleh PHP dari array (bukan HTML manual),
// lengkap dengan pencarian dan filter kategori.
// Anggota 3
// ============================================================
require_once "includes/functions.php";
require_once "data/alat.php";

// Ambil input pencarian & filter dari URL (?q=...&kategori=...)
$kata     = isset($_GET["q"]) ? trim($_GET["q"]) : "";
$kategori = isset($_GET["kategori"]) ? $_GET["kategori"] : "";

// Proses: saring data alat
$hasil = [];
foreach ($alat as $item) {
    $cocokNama     = $kata === "" || stripos($item["nama"], $kata) !== false;
    $cocokKategori = $kategori === "" || $item["kategori"] === $kategori;

    if ($cocokNama && $cocokKategori) {
        $hasil[] = $item;
    }
}

$semuaKategori = daftarKategori($alat);

$judulHalaman = "Katalog Alat";
$halamanAktif = "katalog";
require "includes/header.php";
?>

<h1>Katalog Alat Camping</h1>

<form method="get" class="filter">
    <input type="text" name="q" placeholder="Cari alat, misal: tenda" value="<?= e($kata) ?>">
    <select name="kategori">
        <option value="">Semua kategori</option>
        <?php foreach ($semuaKategori as $k): ?>
            <option value="<?= e($k) ?>" <?= $k === $kategori ? "selected" : "" ?>><?= e($k) ?></option>
        <?php endforeach; ?>
    </select>
    <button class="btn" type="submit">Cari</button>
    <a class="btn btn-ghost" href="katalog.php">Reset</a>
</form>

<p class="muted">Menampilkan <?= count($hasil) ?> dari <?= count($alat) ?> alat.</p>

<?php if (count($hasil) === 0): ?>
    <div class="panel kosong">
        <p>Alat tidak ditemukan. Coba kata kunci lain atau pilih semua kategori.</p>
    </div>
<?php else: ?>
    <section class="cards">
        <?php foreach ($hasil as $item): ?>
            <?php $status = getStatusStok($item["stok"]); ?>
            <article class="card">
                <span class="kategori"><?= e($item["kategori"]) ?></span>
                <h3><?= e($item["nama"]) ?></h3>
                <p><?= e($item["deskripsi"]) ?></p>
                <p class="harga"><?= formatRupiah($item["harga"]) ?> <small>/ hari</small></p>
                <p>
                    <span class="badge <?= $status["class"] ?>"><?= $status["label"] ?></span>
                    <small>Stok: <?= $item["stok"] ?></small>
                </p>
                <?php if ($item["stok"] > 0): ?>
                    <a class="btn" href="ketersediaan.php?id=<?= $item["id"] ?>">Cek &amp; sewa</a>
                <?php else: ?>
                    <span class="btn btn-disabled">Stok habis</span>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>

<?php require "includes/footer.php"; ?>
