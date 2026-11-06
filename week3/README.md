# RimbaCamp — Task 01, Task 02, Task 03

Website demo penyewaan alat camping yang dibuat dengan HTML, CSS, dan JavaScript murni (tanpa framework).

## Struktur folder

```text
RimbaCamp-Task-1-2-3/
├── index.html
├── css/
│   └── style.css
├── js/
│   └── script.js
└── README.md
```

## Cara menjalankan di VS Code

1. Ekstrak file ZIP.
2. Buka Visual Studio Code.
3. Pilih **File > Open Folder**, lalu pilih folder `RimbaCamp-Task-1-2-3`.
4. Pastikan struktur folder dan file sama seperti daftar di atas.
5. Pasang ekstensi **Live Server** jika belum tersedia.
6. Klik kanan `index.html`, lalu pilih **Open with Live Server**.
7. Browser akan membuka website RimbaCamp.

Website juga dapat dibuka dengan klik dua kali `index.html`, tetapi Live Server lebih disarankan.

## Penjelasan tugas

### Task 01 — Select & Change
Lokasi kode: `js/script.js`, bagian `TASK 01 — SELECT & CHANGE`.

JavaScript memilih elemen:
- `document.querySelector("#hero-title")`
- `document.querySelector("#hero-description")`
- `document.querySelector("#hero-badge")`

Lalu JavaScript mengubah teks judul dan deskripsi, serta menambahkan atribut pada badge. Perubahan terlihat pada bagian hero saat halaman dibuka.

### Task 02 — Handle User Event
Lokasi kode: `js/script.js`, bagian `TASK 02 — HANDLE USER EVENT`.

Interaksi yang tersedia:
- Event `input` untuk pencarian perlengkapan.
- Event `change` untuk filter kategori dan pengurutan harga.
- Event `click` untuk reset pencarian.
- Event `click` untuk membuka/menutup navigasi mobile.
- Event `click` untuk menampilkan Riwayat Pesanan.
- Event `click` untuk menampilkan Dashboard Admin.

### Task 03 — Build One Complete Interaction
Lokasi kode: `js/script.js`, bagian `TASK 03 — BUILD ONE COMPLETE INTERACTION`.

Alur demonstrasi:
1. Pengguna menekan tombol **Sewa Sekarang** pada salah satu kartu produk.
2. JavaScript menangkap event klik dan mencari data produk berdasarkan ID.
3. Modal pemesanan muncul dan menampilkan nama serta harga produk.
4. Pengguna mengisi nama, nomor WhatsApp, tanggal mulai, durasi, dan jumlah alat.
5. JavaScript menghitung estimasi biaya secara otomatis.
6. Saat form dikirim, JavaScript memvalidasi data dan mengecek stok.
7. Pesanan disimpan ke `localStorage`.
8. Website menampilkan notifikasi dan memperbarui katalog, riwayat pesanan, serta dashboard admin.

## Cara menguji

1. **Task 01:** muat ulang halaman dan amati judul serta deskripsi hero.
2. **Task 02:** ketik `tenda` di pencarian, pilih kategori, ubah urutan harga, lalu coba menu Riwayat Pesanan dan Dashboard Admin.
3. **Task 03:** klik **Sewa Sekarang** pada produk, isi form, ubah durasi atau jumlah untuk melihat total biaya, lalu klik **Kirim Pesanan**. Buka Riwayat Pesanan untuk melihat hasilnya.

## Catatan batasan demo

- Data produk tersimpan di array JavaScript.
- Data pesanan tersimpan di `localStorage` browser.
- Dashboard admin hanya membaca data pesanan dari browser yang sama.
- Belum ada backend, database online, autentikasi admin, payment gateway, atau integrasi WhatsApp.
- Karena ini tugas interaksi JavaScript front-end, pemesanan pada versi ini merupakan simulasi alur pemesanan, bukan transaksi sewa sungguhan.
