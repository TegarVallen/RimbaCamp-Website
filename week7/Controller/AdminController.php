<?php
/**
 * AdminController
 * Login admin, dashboard, ubah status pesanan, logout.
 */
class AdminController extends Controller
{
    private const ADMIN_USER = 'admin';
    private const ADMIN_PASS = 'admin123';

    private function isLoggedIn(): bool
    {
        return !empty($_SESSION['admin']);
    }

    private function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('admin/login');
        }
    }

    public function login(): void
    {
        if ($this->isLoggedIn()) {
            $this->redirect('admin/dashboard');
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (($_POST['user'] ?? '') === self::ADMIN_USER && ($_POST['pass'] ?? '') === self::ADMIN_PASS) {
                $_SESSION['admin'] = true;
                $this->redirect('admin/dashboard');
            }
            $error = 'Username atau password salah.';
        }

        $this->render('admin-login-page', ['pageTitle' => 'Login Admin', 'error' => $error]);
    }

    public function dashboard(): void
    {
        $this->requireLogin();

        $today = date('Y-m-d');
        $stock = [];
        foreach ($this->equipments->all() as $item) {
            $stock[] = [
                'item'      => $item,
                'booked'    => $this->orders->bookedQty($item->getId(), $today, $today),
                'available' => $this->orders->availableQty($item, $today, $today),
            ];
        }

        $this->render('admin-dashboard-page', [
            'pageTitle' => 'Dashboard Admin',
            'stats'     => [
                'total'    => $this->orders->count(),
                'pending'  => $this->orders->countByStatus(Order::STATUS_PENDING),
                'approved' => $this->orders->countByStatus(Order::STATUS_APPROVED),
                'revenue'  => $this->orders->totalRevenue(),
            ],
            'orderList' => $this->orders->all(),
            'stock'     => $stock,
        ]);
    }

    /** Ubah status pesanan (POST) */
    public function process(): void
    {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->orders->updateStatus((int) ($_POST['order_id'] ?? 0), $_POST['status'] ?? '');
        }
        $this->redirect('admin/dashboard');
    }

    public function logout(): void
    {
        unset($_SESSION['admin']);
        $this->redirect('admin/login');
    }
}
