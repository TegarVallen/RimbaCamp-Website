<?php
/**
 * Class Order
 * Representasi satu pesanan sewa alat camping.
 */
class Order
{
    public const STATUS_PENDING  = 'Menunggu';
    public const STATUS_APPROVED = 'Disetujui';
    public const STATUS_DONE     = 'Selesai';
    public const STATUS_CANCELED = 'Dibatalkan';

    private int $id;
    private string $customerName;
    private string $phone;
    private int $equipmentId;
    private string $equipmentName;
    private int $qty;
    private string $startDate;
    private string $endDate;
    private int $total;
    private string $status;
    private string $createdAt;

    public function __construct(
        int $id,
        string $customerName,
        string $phone,
        int $equipmentId,
        string $equipmentName,
        int $qty,
        string $startDate,
        string $endDate,
        int $total,
        string $status = self::STATUS_PENDING,
        ?string $createdAt = null
    ) {
        $this->id            = $id;
        $this->customerName  = $customerName;
        $this->phone         = $phone;
        $this->equipmentId   = $equipmentId;
        $this->equipmentName = $equipmentName;
        $this->qty           = $qty;
        $this->startDate     = $startDate;
        $this->endDate       = $endDate;
        $this->total         = $total;
        $this->status        = $status;
        $this->createdAt     = $createdAt ?? date('Y-m-d H:i');
    }

    // ===== Getter =====
    public function getId(): int { return $this->id; }
    public function getCustomerName(): string { return $this->customerName; }
    public function getPhone(): string { return $this->phone; }
    public function getEquipmentId(): int { return $this->equipmentId; }
    public function getEquipmentName(): string { return $this->equipmentName; }
    public function getQty(): int { return $this->qty; }
    public function getStartDate(): string { return $this->startDate; }
    public function getEndDate(): string { return $this->endDate; }
    public function getTotal(): int { return $this->total; }
    public function getStatus(): string { return $this->status; }
    public function getCreatedAt(): string { return $this->createdAt; }

    // ===== Setter terkontrol (hanya status yang boleh diubah) =====
    public function setStatus(string $status): void
    {
        if (in_array($status, self::allStatuses(), true)) {
            $this->status = $status;
        }
    }

    // ===== Method =====
    public static function allStatuses(): array
    {
        return [self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_DONE, self::STATUS_CANCELED];
    }

    public function getFormattedTotal(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    /** Pesanan aktif = masih memakai stok alat */
    public function isActive(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED], true);
    }

    public function overlaps(string $start, string $end): bool
    {
        return $this->startDate <= $end && $this->endDate >= $start;
    }

    public function getStatusClass(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING  => 'badge-warning',
            self::STATUS_APPROVED => 'badge-info',
            self::STATUS_DONE     => 'badge-success',
            default               => 'badge-danger',
        };
    }

    // Konversi dari/ke array agar bisa disimpan di session
    public function toArray(): array
    {
        return [
            $this->id, $this->customerName, $this->phone, $this->equipmentId,
            $this->equipmentName, $this->qty, $this->startDate, $this->endDate,
            $this->total, $this->status, $this->createdAt,
        ];
    }

    public static function fromArray(array $d): Order
    {
        return new Order(...$d);
    }
}
