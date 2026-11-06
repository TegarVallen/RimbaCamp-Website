<?php
// WEEK 5 - TASK 02 (function): logika penyewaan

// Mengubah angka menjadi format rupiah, contoh: 75000 -> Rp75.000
function formatRupiah($angka)
{
    return "Rp" . number_format($angka, 0, ",", ".");
}

// Menentukan status ketersediaan alat berdasarkan stok (conditional)
function statusStok($stok)
{
    if ($stok > 3) {
        return "Tersedia";
    } elseif ($stok > 0) {
        return "Hampir habis";
    }
    return "Habis";
}

// Class CSS untuk badge sesuai status
function kelasBadge($stok)
{
    if ($stok > 0) {
        return "ok";
    }
    return "no";
}

// Menghitung total biaya sewa
function hitungTotal($harga, $hari, $jumlah)
{
    return $harga * $hari * $jumlah;
}

// Mencari satu alat berdasarkan id (loop)
function cariAlat($daftar, $id)
{
    foreach ($daftar as $alat) {
        if ($alat["id"] === $id) {
            return $alat;
        }
    }
    return null;
}

// Aman untuk dicetak ke HTML
function e($teks)
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, "UTF-8");
}
