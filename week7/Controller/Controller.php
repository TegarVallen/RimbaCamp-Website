<?php
/**
 * Base Controller
 * Tugas: menerima Model, menampilkan View, dan mengalihkan halaman.
 */
abstract class Controller
{
    protected EquipmentRepository $equipments;
    protected OrderManager $orders;

    public function __construct(EquipmentRepository $equipments, OrderManager $orders)
    {
        $this->equipments = $equipments;
        $this->orders     = $orders;
    }

    /** Controller -> View: kirim data ke file View lalu tampilkan sebagai HTML */
    protected function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $dir = __DIR__ . '/../View/';
        require $dir . 'layout/header.php';
        require $dir . $view . '.php';
        require $dir . 'layout/footer.php';
    }

    protected function redirect(string $route, array $params = []): void
    {
        header('Location: ' . url($route, $params));
        exit;
    }

    /** Dipakai EquipmentController::show() dan OrderController::process() */
    protected function renderOrderForm(Equipment $item, array $old, array $errors = []): void
    {
        $days = max(1, (int) ((strtotime($old['tgl_selesai']) - strtotime($old['tgl_mulai'])) / 86400) + 1);
        $qty  = max(1, (int) $old['jumlah']);

        $this->render('order-form-page', [
            'pageTitle' => 'Pemesanan',
            'item'      => $item,
            'old'       => $old,
            'errors'    => $errors,
            'days'      => $days,
            'estimate'  => $item->calculateTotal($qty, $days),
        ]);
    }
}
