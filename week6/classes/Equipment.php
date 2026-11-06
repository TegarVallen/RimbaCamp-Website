<?php
/**
 * Class Equipment
 * Representasi satu jenis alat camping yang bisa disewa.
 * Encapsulation: semua property private, diakses lewat getter.
 */
class Equipment
{
    private int $id;
    private string $name;
    private string $category;
    private int $pricePerDay;
    private int $stock;
    private string $description;
    private string $icon;

    // Constructor: menginisialisasi data object
    public function __construct(
        int $id,
        string $name,
        string $category,
        int $pricePerDay,
        int $stock,
        string $description,
        string $icon = '⛺'
    ) {
        $this->id          = $id;
        $this->name        = $name;
        $this->category    = $category;
        $this->pricePerDay = $pricePerDay;
        $this->stock       = $stock;
        $this->description = $description;
        $this->icon        = $icon;
    }

    // ===== Getter =====
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getCategory(): string { return $this->category; }
    public function getPricePerDay(): int { return $this->pricePerDay; }
    public function getStock(): int { return $this->stock; }
    public function getDescription(): string { return $this->description; }
    public function getIcon(): string { return $this->icon; }

    // ===== Method (perilaku object) =====
    public function getFormattedPrice(): string
    {
        return 'Rp ' . number_format($this->pricePerDay, 0, ',', '.') . ' / hari';
    }

    public function calculateTotal(int $qty, int $days): int
    {
        return $this->pricePerDay * $qty * $days;
    }

    public function matches(string $keyword): bool
    {
        if ($keyword === '') {
            return true;
        }
        $keyword = mb_strtolower($keyword);
        return str_contains(mb_strtolower($this->name), $keyword)
            || str_contains(mb_strtolower($this->description), $keyword);
    }
}
