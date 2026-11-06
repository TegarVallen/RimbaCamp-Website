<?php
/**
 * EquipmentController
 * Menangani request katalog alat: index() dan show().
 */
class EquipmentController extends Controller
{
    /** Katalog + pencarian + filter + cek ketersediaan */
    public function index(): void
    {
        // 1. REQUEST diterima
        $keyword  = trim($_GET['q'] ?? '');
        $category = $_GET['kategori'] ?? '';
        $start    = $_GET['tgl_mulai'] ?? '';
        $end      = $_GET['tgl_selesai'] ?? '';

        $checkDates = ($start !== '' && $end !== '' && $start <= $end);
        $error = ($start !== '' && $end !== '' && $start > $end)
            ? 'Tanggal selesai harus setelah tanggal mulai.' : '';

        // 2. Controller meminta data ke MODEL
        $rows = [];
        foreach ($this->equipments->search($keyword, $category) as $item) {
            $available = $checkDates
                ? $this->orders->availableQty($item, $start, $end)
                : $item->getStock();
            $rows[] = ['item' => $item, 'available' => $available];
        }

        // 3. Controller meneruskan data ke VIEW
        $this->render('equipment-page', [
            'pageTitle'  => 'Katalog Alat',
            'rows'       => $rows,
            'categories' => $this->equipments->categories(),
            'keyword'    => $keyword,
            'category'   => $category,
            'start'      => $start,
            'end'        => $end,
            'checkDates' => $checkDates,
            'error'      => $error,
        ]);
    }

    /** Form pemesanan untuk satu alat (index.php?r=equipment/show&id=1) */
    public function show(): void
    {
        $item = $this->equipments->find((int) ($_GET['id'] ?? 0));
        if (!$item) {
            $this->redirect('equipment/index');
        }

        $old = [
            'nama'        => '',
            'telp'        => '',
            'jumlah'      => 1,
            'tgl_mulai'   => $_GET['tgl_mulai'] ?? date('Y-m-d'),
            'tgl_selesai' => $_GET['tgl_selesai'] ?? date('Y-m-d', strtotime('+1 day')),
        ];
        $this->renderOrderForm($item, $old);
    }
}
