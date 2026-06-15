# WSL TOPSIS CRM

CRM prospek properti berbasis Laravel dengan modul SPK TOPSIS untuk memprioritaskan calon pembeli berdasarkan **buyer readiness**.

## Fokus Sistem

Alternatif pada sistem ini adalah **calon pembeli properti**, bukan unit properti. Ranking dihitung dari 8 kriteria aktif:

1. Tingkat minat calon pembeli
2. Kemampuan finansial
3. Intensitas interaksi dengan tim sales
4. Kesiapan dokumen administratif
5. Riwayat kunjungan atau peninjauan lokasi
6. Estimasi waktu pembelian
7. Kesesuaian kemampuan Down Payment (DP)
8. Ketersediaan unit yang diminati

Seluruh kriteria default bertipe `BENEFIT`, sehingga nilai yang lebih tinggi dianggap lebih baik.

## Perubahan Penting

- Form prospek sekarang menyimpan preferensi arah unit: `TIMUR`, `BARAT`, `SELATAN`, `UTARA`.
- Preferensi arah unit dipakai sebagai data konteks sales, **bukan** komponen skor TOPSIS.
- Ranking hanya dihitung untuk prospek yang evaluasinya lengkap pada semua kriteria aktif.
- Jika definisi kriteria berubah, `spk_score` akan dikosongkan sampai prospek dievaluasi ulang secara lengkap. Ini sengaja untuk mencegah ranking lama menjadi menyesatkan.

## Ringkasan Fitur

- Login dengan role `admin`, `sales`, dan `pimpinan`
- Manajemen prospek dan pipeline follow up
- Evaluasi buyer readiness per prospek
- Perhitungan skor `spk_score` otomatis dengan metode TOPSIS
- Dashboard prioritas tindak lanjut
- Pengelolaan kriteria dan sub-kriteria oleh admin

## Cara Kerja Skor

Alur utamanya:

1. Sales membuat prospek
2. Sales mengisi evaluasi untuk seluruh kriteria aktif
3. Sistem menyimpan `raw_value` berdasarkan sub-kriteria yang dipilih
4. Semua prospek dengan evaluasi lengkap dihitung ulang menggunakan TOPSIS
5. Hasilnya disimpan ke `prospects.spk_score`

Rumus yang dipakai:

```text
r_ij = x_ij / sqrt(sum(x_ij^2))
y_ij = w_j * r_ij
V_i  = D_i^- / (D_i^- + D_i^+)
```

Implementasi utama ada di:

- `app/Services/DecisionSupport/NormalizationService.php`
- `app/Services/DecisionSupport/TopsisCalculatorService.php`
- `app/Http/Controllers/Sales/EvaluationController.php`

## Sinkronisasi Kriteria

Definisi kriteria default dipusatkan di:

- `app/Support/CriteriaCatalog.php`

Digunakan oleh:

- `database/seeders/CriteriaSeeder.php`
- `database/migrations/2026_04_17_000001_sync_buyer_readiness_criteria.php`
- `app/Http/Controllers/GuideController.php`

Migration sinkronisasi akan:

- memperbarui `C1` sampai `C8`
- menonaktifkan kriteria di luar katalog default
- mengganti sub-kriteria default
- mengosongkan `spk_score` agar ranking lama tidak terbaca sebagai ranking valid

## Seeder

Seeder demo sekarang membentuk prospek berdasarkan arketipe buyer readiness:

- `priority`
- `growing`
- `nurture`
- `early`

Seeder terkait:

- `database/seeders/CriteriaSeeder.php`
- `database/seeders/ProspectSeeder.php`
- `database/seeders/UserSeeder.php`
- `database/seeders/PimpinanSeeder.php`

## Menjalankan Proyek

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm run dev
php artisan serve
```

Jika database lama sudah terisi dan Anda menarik revisi ini, jalankan minimal:

```bash
php artisan migrate
php artisan db:seed --class=CriteriaSeeder
```

Lalu evaluasi ulang prospek yang ingin ikut ranking baru.

## Testing

Unit test TOPSIS ada di:

- `tests/Unit/TopsisCalculationTest.php`

Jalankan:

```bash
php artisan test
```
