<?php
/**
 * OrderController
 * Menangani pemesanan online: process() dan history().
 */
class OrderController extends Controller
{
    /** Memproses form pemesanan (POST) */
    public function process(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('equipment/index');
        }

        $item = $this->equipments->find((int) ($_POST['id'] ?? 0));
        if (!$item) {
            $this->redirect('equipment/index');
        }

        $old = [
            'nama'        => trim($_POST['nama'] ?? ''),
            'telp'        => trim($_POST['telp'] ?? ''),
            'jumlah'      => (int) ($_POST['jumlah'] ?? 1),
            'tgl_mulai'   => $_POST['tgl_mulai'] ?? '',
            'tgl_selesai' => $_POST['tgl_selesai'] ?? '',
        ];

        // Validasi (logika proses = tanggung jawab Controller)
        $errors = [];
        if ($old['nama'] === '') $errors[] = 'Nama wajib diisi.';
        if (!preg_match('/^[0-9]{9,15}$/', $old['telp'])) $errors[] = 'Nomor HP harus 9-15 digit angka.';
        if ($old['jumlah'] < 1) $errors[] = 'Jumlah minimal 1.';
        if ($old['tgl_mulai'] === '' || $old['tgl_selesai'] === '') {
            $errors[] = 'Tanggal sewa wajib diisi.';
        } else {
            if ($old['tgl_mulai'] < date('Y-m-d')) $errors[] = 'Tanggal mulai tidak boleh sebelum hari ini.';
            if ($old['tgl_selesai'] < $old['tgl_mulai']) $errors[] = 'Tanggal selesai harus setelah tanggal mulai.';
        }

        // Cek stok lewat MODEL
        if (!$errors) {
            $available = $this->orders->availableQty($item, $old['tgl_mulai'], $old['tgl_selesai']);
            if ($old['jumlah'] > $available) {
                $errors[] = "Stok tidak cukup. Tersedia hanya $available unit pada tanggal tersebut.";
            }
        }

        if ($errors) {
            $this->renderOrderForm($item, $old, $errors);
            return;
        }

        // Simpan lewat MODEL
        $this->orders->create($item, $old['nama'], $old['telp'], $old['jumlah'], $old['tgl_mulai'], $old['tgl_selesai']);
        $this->redirect('order/history', ['telp' => $old['telp'], 'sukses' => 1]);
    }

    /** Riwayat pesanan berdasarkan nomor HP */
    public function history(): void
    {
        $phone = trim($_GET['telp'] ?? '');
        $list  = $phone !== '' ? $this->orders->byPhone($phone) : [];

        $this->render('history-page', [
            'pageTitle' => 'Riwayat Pesanan',
            'phone'     => $phone,
            'list'      => $list,
            'sukses'    => isset($_GET['sukses']),
        ]);
    }
}
