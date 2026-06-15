1. Tabel Utama

users: Tambah kolom role (enum).

criterias:

name (string)

weight (float) -> Bobot WSM (Total harus 100/1).

type (enum: BENEFIT/COST).

code (string: C1, C2).

sub_criterias (Master Data Nilai):

criteria_id (FK)

label (string) -> Misal: "Gaji 5-10 Juta"

value (int) -> Misal: 3

prospects:

sales_id (FK to users)

name, phone, address

status (enum)

final_score (float, nullable) -> Hasil hitungan TOPSIS disimpan disini untuk query cepat.

2. Tabel Transaksi (Nilai)

evaluations:

prospect_id (FK)

criteria_id (FK)

sub_criteria_id (FK) -> Menyimpan pilihan user.

raw_value (int) -> Menyimpan nilai angka (1-5) dari sub_criteria saat save (snapshot).