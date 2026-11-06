# CampingKu - Website Penyewaan Alat Camping (PHP OOP)

Sprint 05 - Pertemuan 6 Web Programming (From Procedural to Object-Oriented).

## Fitur
Katalog Alat, Pencarian & Filter, Cek Ketersediaan, Pemesanan Online, Riwayat Pesanan, Dashboard Admin.

## Penerapan OOP
| Konsep | Implementasi |
|---|---|
| Class | `Equipment`, `Order`, `EquipmentRepository`, `OrderManager` |
| Object | `new Equipment(...)`, `new Order(...)`, `$repo`, `$orders` |
| Property | `private $name`, `$pricePerDay`, `$stock`, `$status`, dll |
| Method | `getFormattedPrice()`, `calculateTotal()`, `availableQty()`, `isActive()` |
| Constructor | `__construct()` di setiap class |
| Encapsulation | Semua property `private`, akses lewat getter / setter terkontrol |
| Dynamic HTML | Semua halaman dihasilkan dari object lewat PHP |

## Cara Menjalankan
```bash
php -S localhost:8000
```
Buka http://localhost:8000. Admin demo: `admin` / `admin123`.

> Data pesanan disimpan di `$_SESSION` (tanpa database) sesuai fokus Sprint 05.

## Commit & Push GitHub
```bash
git init
git add .
git commit -m "Sprint 05: CampingKu dengan PHP OOP"
git branch -M main
git remote add origin https://github.com/USERNAME/campingku.git
git push -u origin main
```
