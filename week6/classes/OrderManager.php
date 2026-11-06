<?php
require_once __DIR__ . '/Order.php';
require_once __DIR__ . '/Equipment.php';

/**
 * Class OrderManager
 * Mengelola kumpulan Order (disimpan di $_SESSION agar tanpa database).
 */
class OrderManager
{
    /** @var Order[] */
    private array $orders = [];

    public function __construct()
    {
        if (!isset($_SESSION['orders'])) {
            $_SESSION['orders'] = $this->sampleData();
        }
        foreach ($_SESSION['orders'] as $row) {
            $this->orders[] = Order::fromArray($row);
        }
    }

    /** Data contoh agar dashboard admin tidak kosong saat demo */
    private function sampleData(): array
    {
        $today = date('Y-m-d');
        return [
            [1, 'Budi Santoso', '081234567890', 1, 'Tenda Dome 4 Orang', 2, $today, date('Y-m-d', strtotime('+2 days')), 360000, Order::STATUS_APPROVED, date('Y-m-d H:i')],
            [2, 'Siti Aminah', '085711112222', 3, 'Carrier 60L', 1, date('Y-m-d', strtotime('-5 days')), date('Y-m-d', strtotime('-3 days')), 90000, Order::STATUS_DONE, date('Y-m-d H:i', strtotime('-6 days'))],
        ];
    }

    private function save(): void
    {
        $_SESSION['orders'] = array_map(fn(Order $o) => $o->toArray(), $this->orders);
    }

    /** @return Order[] */
    public function all(): array
    {
        return array_reverse($this->orders);
    }

    public function byPhone(string $phone): array
    {
        return array_values(array_filter($this->all(), fn(Order $o) => $o->getPhone() === $phone));
    }

    public function nextId(): int
    {
        $max = 0;
        foreach ($this->orders as $o) {
            $max = max($max, $o->getId());
        }
        return $max + 1;
    }

    /** Jumlah alat yang sudah dipesan pada rentang tanggal tertentu */
    public function bookedQty(int $equipmentId, string $start, string $end): int
    {
        $total = 0;
        foreach ($this->orders as $o) {
            if ($o->getEquipmentId() === $equipmentId && $o->isActive() && $o->overlaps($start, $end)) {
                $total += $o->getQty();
            }
        }
        return $total;
    }

    /** Cek ketersediaan: stok - yang sudah dipesan */
    public function availableQty(Equipment $e, string $start, string $end): int
    {
        return max(0, $e->getStock() - $this->bookedQty($e->getId(), $start, $end));
    }

    public function create(Equipment $e, string $name, string $phone, int $qty, string $start, string $end): Order
    {
        $days  = (int) ((strtotime($end) - strtotime($start)) / 86400) + 1;
        $order = new Order(
            $this->nextId(), $name, $phone, $e->getId(), $e->getName(),
            $qty, $start, $end, $e->calculateTotal($qty, $days)
        );
        $this->orders[] = $order;
        $this->save();
        return $order;
    }

    public function updateStatus(int $id, string $status): void
    {
        foreach ($this->orders as $o) {
            if ($o->getId() === $id) {
                $o->setStatus($status);
            }
        }
        $this->save();
    }

    // ===== Statistik untuk dashboard admin =====
    public function countByStatus(string $status): int
    {
        return count(array_filter($this->orders, fn(Order $o) => $o->getStatus() === $status));
    }

    public function totalRevenue(): int
    {
        $sum = 0;
        foreach ($this->orders as $o) {
            if (in_array($o->getStatus(), [Order::STATUS_APPROVED, Order::STATUS_DONE], true)) {
                $sum += $o->getTotal();
            }
        }
        return $sum;
    }

    public function count(): int
    {
        return count($this->orders);
    }
}
