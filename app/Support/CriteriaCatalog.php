<?php

declare(strict_types=1);

namespace App\Support;

final class CriteriaCatalog
{
    public static function definitions(): array
    {
        return [
            [
                'code' => 'C1',
                'name' => 'Tingkat Minat Calon Pembeli',
                'type' => 'BENEFIT',
                'weight' => 0.16,
                'sub_criterias' => [
                    ['label' => 'Minat masih sangat rendah', 'value' => 1, 'sequence' => 1],
                    ['label' => 'Masih tahap membandingkan', 'value' => 2, 'sequence' => 2],
                    ['label' => 'Mulai bertanya aktif', 'value' => 3, 'sequence' => 3],
                    ['label' => 'Meminta simulasi atau penawaran', 'value' => 4, 'sequence' => 4],
                    ['label' => 'Sudah menyatakan minat serius', 'value' => 5, 'sequence' => 5],
                ],
            ],
            [
                'code' => 'C2',
                'name' => 'Kemampuan Finansial',
                'type' => 'BENEFIT',
                'weight' => 0.15,
                'sub_criterias' => [
                    ['label' => 'Sumber dana belum jelas', 'value' => 1, 'sequence' => 1],
                    ['label' => 'Butuh skema pembiayaan berat', 'value' => 2, 'sequence' => 2],
                    ['label' => 'Anggaran cukup tetapi ketat', 'value' => 3, 'sequence' => 3],
                    ['label' => 'Pembiayaan cukup siap', 'value' => 4, 'sequence' => 4],
                    ['label' => 'Dana atau KPR siap diproses', 'value' => 5, 'sequence' => 5],
                ],
            ],
            [
                'code' => 'C3',
                'name' => 'Intensitas Interaksi dengan Tim Sales',
                'type' => 'BENEFIT',
                'weight' => 0.13,
                'sub_criterias' => [
                    ['label' => 'Sulit dihubungi', 'value' => 1, 'sequence' => 1],
                    ['label' => 'Respon sesekali', 'value' => 2, 'sequence' => 2],
                    ['label' => 'Komunikasi rutin', 'value' => 3, 'sequence' => 3],
                    ['label' => 'Aktif follow up', 'value' => 4, 'sequence' => 4],
                    ['label' => 'Sangat responsif dan proaktif', 'value' => 5, 'sequence' => 5],
                ],
            ],
            [
                'code' => 'C4',
                'name' => 'Kesiapan Dokumen Administratif',
                'type' => 'BENEFIT',
                'weight' => 0.12,
                'sub_criterias' => [
                    ['label' => 'Belum menyiapkan dokumen', 'value' => 1, 'sequence' => 1],
                    ['label' => 'Baru sebagian kecil tersedia', 'value' => 2, 'sequence' => 2],
                    ['label' => 'Dokumen utama sudah ada', 'value' => 3, 'sequence' => 3],
                    ['label' => 'Hampir lengkap', 'value' => 4, 'sequence' => 4],
                    ['label' => 'Lengkap dan siap verifikasi', 'value' => 5, 'sequence' => 5],
                ],
            ],
            [
                'code' => 'C5',
                'name' => 'Riwayat Kunjungan atau Peninjauan Lokasi',
                'type' => 'BENEFIT',
                'weight' => 0.10,
                'sub_criterias' => [
                    ['label' => 'Belum pernah melihat lokasi', 'value' => 1, 'sequence' => 1],
                    ['label' => 'Baru melihat materi promosi', 'value' => 2, 'sequence' => 2],
                    ['label' => 'Sudah diskusi detail lokasi', 'value' => 3, 'sequence' => 3],
                    ['label' => 'Sudah survey satu kali', 'value' => 4, 'sequence' => 4],
                    ['label' => 'Sudah survey ulang atau ajak keluarga', 'value' => 5, 'sequence' => 5],
                ],
            ],
            [
                'code' => 'C6',
                'name' => 'Estimasi Waktu Pembelian',
                'type' => 'BENEFIT',
                'weight' => 0.12,
                'sub_criterias' => [
                    ['label' => 'Di atas 6 bulan', 'value' => 1, 'sequence' => 1],
                    ['label' => 'Sekitar 3 sampai 6 bulan', 'value' => 2, 'sequence' => 2],
                    ['label' => 'Sekitar 1 sampai 3 bulan', 'value' => 3, 'sequence' => 3],
                    ['label' => 'Kurang dari 1 bulan', 'value' => 4, 'sequence' => 4],
                    ['label' => 'Siap transaksi dalam waktu dekat', 'value' => 5, 'sequence' => 5],
                ],
            ],
            [
                'code' => 'C7',
                'name' => 'Kesesuaian Kemampuan Down Payment (DP)',
                'type' => 'BENEFIT',
                'weight' => 0.12,
                'sub_criterias' => [
                    ['label' => 'Jauh di bawah kebutuhan DP', 'value' => 1, 'sequence' => 1],
                    ['label' => 'Masih ada selisih besar', 'value' => 2, 'sequence' => 2],
                    ['label' => 'Cukup mendekati kebutuhan', 'value' => 3, 'sequence' => 3],
                    ['label' => 'Hampir sesuai dengan kebutuhan', 'value' => 4, 'sequence' => 4],
                    ['label' => 'Sudah sesuai atau melebihi kebutuhan', 'value' => 5, 'sequence' => 5],
                ],
            ],
            [
                'code' => 'C8',
                'name' => 'Ketersediaan Unit yang Diminati',
                'type' => 'BENEFIT',
                'weight' => 0.10,
                'sub_criterias' => [
                    ['label' => 'Unit yang dicari belum tersedia', 'value' => 1, 'sequence' => 1],
                    ['label' => 'Pilihan sangat terbatas', 'value' => 2, 'sequence' => 2],
                    ['label' => 'Ada alternatif yang cukup sesuai', 'value' => 3, 'sequence' => 3],
                    ['label' => 'Unit sesuai tersedia', 'value' => 4, 'sequence' => 4],
                    ['label' => 'Unit pilihan utama siap dipesan', 'value' => 5, 'sequence' => 5],
                ],
            ],
        ];
    }

    public static function codes(): array
    {
        return array_column(static::definitions(), 'code');
    }
}
