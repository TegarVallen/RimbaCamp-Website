<?php
// ============================================================
// WEEK 5 - TASK 01: CREATE PHP PAGE
// Halaman PHP yang menampilkan informasi toko penyewaan alat camping.
// Alur: file .php -> diproses server -> HTML dikirim ke browser
// ============================================================
date_default_timezone_set("Asia/Jakarta");

// ---------- Variable informasi toko ----------
$namaToko = "Nyilih Camp's";
$tagline = "Cek stok, bandingkan harga, dan hitung biaya sewa sebelum berangkat.";
$alamat = "Jl. Basecamp No. 12";
$kontak = "0812-3456-7890";
$jamBuka = 8;
$jamTutup = 20;

// ---------- Status toko (conditional) ----------
$jamSekarang = (int) date("G");
if ($jamSekarang >= $jamBuka && $jamSekarang < $jamTutup) {
    $statusToko = "Buka";
    $kelasStatus = "ok";
} else {
    $statusToko = "Tutup";
    $kelasStatus = "no";
}

// ---------- Data & proses (dikerjakan di Task 02) ----------
require "data.php";       // $daftarAlat
require "functions.php";  // function bantu
require "proses.php";     // filter, kategori, hitung sewa
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo e($namaToko); ?> - Week 5</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="hero">
    <div class="wrap">
      <h1><?php echo e($namaToko); ?></h1>
      <p><?php echo e($tagline); ?></p>
      <ul class="info-toko">
        <li>
          Status toko:
          <span class="badge <?php echo $kelasStatus; ?>"><?php echo e($statusToko); ?></span>
        </li>
        <li>Jam operasional: <?php echo $jamBuka; ?>.00 - <?php echo $jamTutup; ?>.00 WIB</li>
        <li>Alamat: <?php echo e($alamat); ?></li>
        <li>Kontak: <?php echo e($kontak); ?></li>
      </ul>
    </div>
  </header>

  <?php require "katalog.php"; // Task 03: konten dinamis dari server ?>

  <footer><?php echo e($namaToko); ?> &middot; Week 5: server-side dengan PHP &middot; &copy; <?php echo date("Y"); ?></footer>
</body>
</html>
