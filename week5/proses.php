<?php
// ============================================================
// WEEK 5 - TASK 02: PROCESS DATA WITH PHP
// Data alat (array) diproses dulu di server sebelum ditampilkan:
// pencarian, filter (conditional + loop), dan hitung sewa (function).
// Alur: DATA -> PHP -> CONDITION / LOOP -> HTML
// ============================================================

// ---------- Input dari URL (variable) ----------
$kataCari = isset($_GET["q"]) ? trim($_GET["q"]) : "";
$kategoriDipilih = isset($_GET["kategori"]) ? $_GET["kategori"] : "";
$hanyaTersedia = isset($_GET["tersedia"]);

// ---------- Kategori unik (loop) ----------
$daftarKategori = [];
foreach ($daftarAlat as $alat) {
    if (!in_array($alat["kategori"], $daftarKategori)) {
        $daftarKategori[] = $alat["kategori"];
    }
}

// ---------- Pencarian & filter (conditional + loop) ----------
$hasil = [];
foreach ($daftarAlat as $alat) {
    $cocokNama = $kataCari === "" || stripos($alat["nama"], $kataCari) !== false;
    $cocokKategori = $kategoriDipilih === "" || $alat["kategori"] === $kategoriDipilih;
    $cocokStok = !$hanyaTersedia || $alat["stok"] > 0;

    if ($cocokNama && $cocokKategori && $cocokStok) {
        $hasil[] = $alat;
    }
}

// ---------- Cek ketersediaan & hitung sewa ----------
$hasilCek = null;
if (isset($_GET["cek"])) {
    $idCek = (int) $_GET["cek"];
    $hari = isset($_GET["hari"]) ? (int) $_GET["hari"] : 1;
    $jumlah = isset($_GET["jumlah"]) ? (int) $_GET["jumlah"] : 1;
    $alatCek = cariAlat($daftarAlat, $idCek);

    if ($alatCek === null) {
        $hasilCek = ["ok" => false, "pesan" => "Alat tidak ditemukan."];
    } elseif ($hari < 1 || $jumlah < 1) {
        $hasilCek = ["ok" => false, "pesan" => "Lama sewa dan jumlah unit minimal 1."];
    } elseif ($jumlah > $alatCek["stok"]) {
        $hasilCek = [
            "ok" => false,
            "pesan" => "Stok tidak cukup. Tersisa " . $alatCek["stok"] . " unit untuk " . $alatCek["nama"] . ".",
        ];
    } else {
        $total = hitungTotal($alatCek["harga_per_hari"], $hari, $jumlah);
        $hasilCek = [
            "ok" => true,
            "pesan" => "Tersedia. Total sewa " . $jumlah . " unit " . $alatCek["nama"] .
                " selama " . $hari . " hari: " . formatRupiah($total) . ".",
        ];
    }
}
