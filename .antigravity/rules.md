# AI CODING GUIDELINES & RULES
**Project:** CRM Property + SPK Hybrid WSM-TOPSIS
**Stack:** Laravel 11, MySQL, Blade (Tailwind), PHP 8.3+

## 1. STRUKTUR & BATASAN FILE (Strict)
- **Max 300 Lines Rule:** Tidak ada file (Controller, Model, Service) yang boleh melebihi 300 baris.
- **Strategy jika file > 300 baris:**
  - Pecah Controller menjadi `Action Classes` (Single Action Controller) atau pindahkan logika ke `Service`.
  - Pecah Model menggunakan `Traits` (misal: `UserRelations`, `UserScopes`).
  - Pecah View Blade menjadi `components` atau `partials` kecil.

## 2. LARAVEL 11 BEST PRACTICES
- Gunakan **Strict Typing** (`declare(strict_types=1);`) di setiap file PHP.
- Gunakan fitur baru Laravel 11:
  - Struktur Config yang ramping (jangan buat file config kecuali perlu).
  - Gunakan `App\Enums` untuk status (jangan hardcode string).
  - Gunakan `FormRequest` untuk semua validasi (Keep Controllers Skinny).
  - Gunakan `API Resources` jika membuat endpoint JSON.
- **Routing:** Gunakan grouping yang rapi di `routes/web.php`.

## 3. MODULAR ARCHITECTURE (Domain Logic)
Jangan taruh logika matematika WSM/TOPSIS di Controller!
- **Services:** Buat folder `app/Services/DecisionSupport` untuk logika hitungan.
- **DTO (Data Transfer Object):** Gunakan DTO untuk mengirim data antar layer jika kompleks.
- **Interfaces:** Gunakan Interface untuk class Kalkulator SPK agar mudah di-testing.

## 4. NAMING CONVENTIONS
- Controller: `ProspectController`, `DashboardController`.
- Service: `TopsisCalculatorService`, `ProspectRankingService`.
- Model: Singular (`Prospect`, `Criteria`).
- Table: Plural (`prospects`, `criterias`).

## 5. SPK LOGIC SPECIFICATION (Hybrid WSM-TOPSIS)
- **WSM:** Digunakan HANYA untuk menentukan bobot awal kriteria.
- **TOPSIS:** Digunakan untuk ranking akhir menggunakan bobot dari WSM.
- **Data:** Input nilai kriteria tidak boleh angka mentah, harus via `sub_criterias` (Skala 1-5).

## 6. 
Hindari Overengineering