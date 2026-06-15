<?php

namespace Database\Seeders;

use App\Models\Criteria;
use App\Models\Prospect;
use App\Models\User;
use App\Services\DecisionSupport\TopsisCalculatorService;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ProspectSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $salesUsers = User::where('role', 'sales')->get();
        if ($salesUsers->isEmpty()) {
            $salesUsers = collect([User::factory()->create(['role' => 'sales'])]);
        }

        $criterias = Criteria::with(['subCriterias' => function ($query) {
            $query->orderBy('value', 'asc');
        }])->where('is_active', true)->get();

        if ($criterias->isEmpty()) {
            $this->command->error('STOP: Jalankan CriteriaSeeder terlebih dahulu!');
            return;
        }

        $archetypes = [
            [
                'label' => 'priority',
                'status_pool' => ['QUALIFIED', 'QUALIFIED', 'CONTACTED', 'WON'],
                'orientation' => 'TIMUR',
                'notes' => 'Sudah meminta simulasi pembayaran, menanyakan stok unit utama, dan siap dijadwalkan untuk proses lanjutan.',
            ],
            [
                'label' => 'growing',
                'status_pool' => ['CONTACTED', 'CONTACTED', 'QUALIFIED'],
                'orientation' => 'SELATAN',
                'notes' => 'Aktif merespons follow up, sedang melengkapi dokumen, dan mulai menyesuaikan kesiapan DP.',
            ],
            [
                'label' => 'nurture',
                'status_pool' => ['NEW', 'CONTACTED', 'CONTACTED'],
                'orientation' => 'BARAT',
                'notes' => 'Masih membandingkan beberapa pilihan unit, namun komunikasi cukup terbuka dan membutuhkan edukasi lanjutan.',
            ],
            [
                'label' => 'early',
                'status_pool' => ['NEW', 'NEW', 'LOST'],
                'orientation' => 'UTARA',
                'notes' => 'Baru masuk funnel, belum ada kepastian sumber dana, dan butuh pendekatan awal yang lebih intens.',
            ],
        ];

        $seedProfiles = [
            ['name' => 'Andi Nurul Akbar', 'phone' => '081242887654', 'archetype' => 'priority'],
            ['name' => 'Dr. Stefanus Wijaya', 'phone' => '081355667788', 'archetype' => 'priority'],
            ['name' => 'Rahmat Hidayat', 'phone' => '085299881122', 'archetype' => 'growing'],
            ['name' => 'Nur Aisyah Daeng Tika', 'phone' => '089677889900', 'archetype' => 'nurture'],
            ['name' => 'Amiruddin Hasan', 'phone' => '0811998877', 'archetype' => 'early'],
        ];

        for ($i = 0; $i < 95; $i++) {
            $seedProfiles[] = [
                'name' => $this->generateMakassarName($faker),
                'phone' => preg_replace('/\D/', '', $faker->phoneNumber),
                'archetype' => $faker->randomElement(array_column($archetypes, 'label')),
            ];
        }

        $archetypesByLabel = collect($archetypes)->keyBy('label');
        $jobs = [
            'Pengusaha kuliner',
            'Pegawai perbankan',
            'Dokter spesialis',
            'ASN Pemprov',
            'Supervisor retail',
            'Kontraktor swasta',
            'Dosen universitas',
        ];

        $this->command->getOutput()->progressStart(count($seedProfiles));

        foreach ($seedProfiles as $profile) {
            $archetype = $archetypesByLabel[$profile['archetype']];
            $status = $faker->randomElement($archetype['status_pool']);
            $job = $faker->randomElement($jobs);

            $prospect = Prospect::create([
                'user_id' => $salesUsers->random()->id,
                'name' => $profile['name'],
                'phone' => $profile['phone'],
                'email' => $faker->unique()->safeEmail,
                'status' => $status,
                'spk_score' => null,
                'metadata' => [
                    'notes' => $job . '. ' . $archetype['notes'],
                    'preferred_orientation' => $archetype['orientation'],
                ],
            ]);

            foreach ($criterias as $criteria) {
                $subs = $criteria->subCriterias->values();
                if ($subs->isEmpty()) {
                    continue;
                }

                $selectedSub = $this->pickForArchetype($subs, $profile['archetype']);

                $prospect->evaluations()->create([
                    'criteria_id' => $criteria->id,
                    'sub_criteria_id' => $selectedSub->id,
                    'raw_value' => $selectedSub->value,
                ]);
            }

            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();

        if (!class_exists(TopsisCalculatorService::class)) {
            $this->command->warn('TopsisCalculatorService belum ditemukan. Data tersimpan tanpa nilai SPK.');
            return;
        }

        $this->command->info('Menghitung skor SPK untuk semua data...');

        try {
            $calculator = app(TopsisCalculatorService::class);
            $allProspects = Prospect::with('evaluations')->get();
            $scores = $calculator->calculate($allProspects);

            Prospect::query()->update(['spk_score' => null]);
            foreach ($scores as $id => $score) {
                Prospect::where('id', $id)->update(['spk_score' => $score]);
            }

            $this->command->info('Perhitungan selesai.');
        } catch (\Throwable $exception) {
            $this->command->warn('Gagal menghitung TOPSIS di seeder: ' . $exception->getMessage());
        }
    }

    private function generateMakassarName($faker): string
    {
        $titles = ['Andi', 'Daeng', 'Puang', 'Karaeng'];
        $useTitle = rand(0, 100) > 60;
        $name = $faker->name;

        return $useTitle
            ? $titles[array_rand($titles)] . ' ' . $faker->firstName . ' ' . $faker->lastName
            : $name;
    }

    private function pickForArchetype($subs, string $archetype)
    {
        $weights = match ($archetype) {
            'priority' => [0, 5, 15, 35, 45],
            'growing' => [5, 15, 35, 30, 15],
            'nurture' => [10, 30, 35, 20, 5],
            default => [40, 30, 20, 10, 0],
        };

        return $this->pickWeighted($subs, $weights);
    }

    private function pickWeighted($subs, array $weights)
    {
        if ($subs->count() !== count($weights)) {
            return $subs->random();
        }

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($weights as $index => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $subs[$index] ?? $subs->last();
            }
        }

        return $subs->last();
    }
}
