<?php
require_once __DIR__ . '/Equipment.php';

/**
 * Class EquipmentRepository
 * Menyimpan & mengolah kumpulan object Equipment (katalog alat).
 */
class EquipmentRepository
{
    /** @var Equipment[] */
    private array $items = [];

    public function __construct()
    {
        $this->items = [
            new Equipment(1, 'Tenda Dome 4 Orang', 'Tenda', 60000, 5, 'Tenda double layer, tahan hujan, muat 4 orang.', '⛺'),
            new Equipment(2, 'Tenda Dome 2 Orang', 'Tenda', 40000, 6, 'Tenda ringan untuk pendakian, muat 2 orang.', '🏕️'),
            new Equipment(3, 'Carrier 60L', 'Tas', 30000, 8, 'Tas carrier 60 liter dengan rain cover.', '🎒'),
            new Equipment(4, 'Sleeping Bag Polar', 'Perlengkapan Tidur', 15000, 12, 'Sleeping bag hangat untuk suhu dingin.', '🛌'),
            new Equipment(5, 'Matras Gulung', 'Perlengkapan Tidur', 8000, 15, 'Matras aluminium foil ringan dan tahan dingin.', '🧘'),
            new Equipment(6, 'Kompor Portable + Gas', 'Alat Masak', 20000, 7, 'Kompor lipat portable beserta 1 tabung gas.', '🔥'),
            new Equipment(7, 'Nesting Set', 'Alat Masak', 12000, 9, 'Satu set panci, wajan, dan gelas lipat.', '🍳'),
            new Equipment(8, 'Headlamp LED', 'Penerangan', 10000, 14, 'Lampu kepala LED terang, baterai tahan lama.', '🔦'),
            new Equipment(9, 'Lampu Tenda', 'Penerangan', 8000, 10, 'Lampu gantung tenda, bisa di-charge.', '💡'),
            new Equipment(10, 'Trekking Pole (Sepasang)', 'Aksesoris', 12000, 10, 'Tongkat trekking aluminium adjustable.', '🥾'),
        ];
    }

    /** @return Equipment[] */
    public function all(): array
    {
        return $this->items;
    }

    public function find(int $id): ?Equipment
    {
        foreach ($this->items as $item) {
            if ($item->getId() === $id) {
                return $item;
            }
        }
        return null;
    }

    public function categories(): array
    {
        $cats = array_map(fn(Equipment $e) => $e->getCategory(), $this->items);
        $cats = array_unique($cats);
        sort($cats);
        return $cats;
    }

    /** Pencarian + filter kategori */
    public function search(string $keyword = '', string $category = ''): array
    {
        return array_values(array_filter($this->items, function (Equipment $e) use ($keyword, $category) {
            $okCategory = ($category === '' || $e->getCategory() === $category);
            return $okCategory && $e->matches($keyword);
        }));
    }
}
