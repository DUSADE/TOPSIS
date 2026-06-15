<x-app-layout>
    <x-slot name="header">
        Panduan Buyer Readiness
    </x-slot>

    <div class="mx-auto max-w-6xl space-y-14 pb-24">
        <section class="overflow-hidden rounded-[2rem] bg-foreground px-8 py-10 text-white shadow-2xl md:px-10">
            <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-end">
                <div>
                    <x-ui.section-label class="text-white/50">Decision Support Guide</x-ui.section-label>
                    <h2 class="font-display text-4xl font-semibold tracking-tight md:text-5xl">
                        Penilaian prospek sekarang berfokus pada kesiapan membeli, bukan atribut unit.
                    </h2>
                    <p class="mt-5 max-w-3xl text-base leading-relaxed text-white/70">
                        Alternatif pada penelitian ini adalah calon pembeli properti. Sistem hanya meranking prospek yang
                        evaluasinya lengkap pada delapan kriteria aktif agar prioritas follow up tetap akurat.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                    <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                        <div class="text-[11px] font-mono uppercase tracking-[0.24em] text-white/50">Skala Penilaian</div>
                        <div class="mt-3 text-3xl font-display font-semibold">1 sampai 5</div>
                        <p class="mt-2 text-sm text-white/70">Semakin tinggi nilai, semakin siap prospek untuk ditindaklanjuti.</p>
                    </div>
                    <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                        <div class="text-[11px] font-mono uppercase tracking-[0.24em] text-white/50">Status Ranking</div>
                        <div class="mt-3 text-3xl font-display font-semibold">Lengkap</div>
                        <p class="mt-2 text-sm text-white/70">Prospek tanpa evaluasi lengkap tidak ikut dihitung dalam ranking TOPSIS.</p>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <x-ui.section-label>Alur Kerja</x-ui.section-label>
            <div class="grid gap-5 md:grid-cols-4">
                @foreach([
                    ['title' => 'Input Prospek', 'desc' => 'Catat identitas, kontak, dan preferensi orientasi unit bila ada.'],
                    ['title' => 'Isi 8 Kriteria', 'desc' => 'Sales memilih satu rubrik penilaian untuk setiap kriteria aktif.'],
                    ['title' => 'Hitung TOPSIS', 'desc' => 'Sistem menormalisasi nilai, menerapkan bobot, lalu menghitung skor preferensi.'],
                    ['title' => 'Prioritaskan Follow Up', 'desc' => 'Tim fokus pada prospek dengan skor tertinggi dan sinyal kesiapan paling kuat.'],
                ] as $step)
                    <div class="sunrise-panel rounded-[1.5rem] p-6">
                        <div class="text-[11px] font-mono uppercase tracking-[0.24em] text-primary">Step</div>
                        <h3 class="mt-3 text-2xl font-display font-semibold text-foreground">{{ $step['title'] }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-muted-foreground">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section>
            <div class="flex items-end justify-between gap-4">
                <div>
                    <x-ui.section-label>Delapan Kriteria</x-ui.section-label>
                    <h3 class="text-3xl font-display font-semibold tracking-tight text-foreground">Buyer readiness matrix</h3>
                </div>
                <div class="rounded-2xl border border-border/70 bg-white px-4 py-3 text-sm text-muted-foreground">
                    Total bobot:
                    <span class="font-semibold text-foreground">{{ number_format(collect($criteriaDefinitions)->sum('weight') * 100, 0) }}%</span>
                </div>
            </div>

            <div class="mt-8 grid gap-5 md:grid-cols-2">
                @foreach($criteriaDefinitions as $criteria)
                    <div class="sunrise-panel rounded-[1.75rem] p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-[11px] font-mono uppercase tracking-[0.24em] text-primary">{{ $criteria['code'] }}</div>
                                <h4 class="mt-3 text-2xl font-display font-semibold text-foreground">{{ $criteria['name'] }}</h4>
                            </div>
                            <x-ui.badge color="primary">{{ number_format($criteria['weight'] * 100, 0) }}%</x-ui.badge>
                        </div>

                        <div class="mt-5 space-y-2">
                            @foreach($criteria['sub_criterias'] as $sub)
                                <div class="flex items-center justify-between rounded-2xl border border-border/60 bg-white/90 px-4 py-3">
                                    <span class="text-sm text-foreground/80">{{ $sub['label'] }}</span>
                                    <span class="font-mono text-sm font-semibold text-primary">{{ number_format($sub['value'], 0) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <x-ui.card class="border-none shadow-glass">
                <x-slot name="header">
                    Tipe Kriteria
                </x-slot>

                <div class="space-y-4 text-sm leading-relaxed text-muted-foreground">
                    <p>
                        Seluruh kriteria buyer readiness saat ini menggunakan tipe <strong class="text-foreground">BENEFIT</strong>.
                        Artinya nilai yang lebih tinggi selalu dianggap lebih baik.
                    </p>
                    <p>
                        Contoh: prospek yang sudah siap DP dan telah melakukan survey ulang akan menghasilkan nilai yang
                        lebih tinggi daripada prospek yang baru bertanya awal.
                    </p>
                </div>
            </x-ui.card>

            <x-ui.card class="border-none shadow-glass">
                <x-slot name="header">
                    Preferensi Arah Unit
                </x-slot>

                <div class="space-y-4 text-sm leading-relaxed text-muted-foreground">
                    <p>
                        Form data prospek sekarang menyimpan preferensi arah unit: <strong class="text-foreground">Timur, Barat, Selatan, atau Utara</strong>.
                    </p>
                    <p>
                        Informasi ini membantu sales memahami kebutuhan cahaya pagi dan kenyamanan unit, tetapi tidak
                        masuk ke skor TOPSIS buyer readiness.
                    </p>
                </div>
            </x-ui.card>
        </section>

        <section class="overflow-hidden rounded-[2rem] border border-border/70 bg-white shadow-glass">
            <div class="border-b border-border/70 px-8 py-6">
                <x-ui.section-label>The Engine</x-ui.section-label>
                <h3 class="text-3xl font-display font-semibold tracking-tight text-foreground">Model perhitungan TOPSIS</h3>
                <p class="mt-3 max-w-3xl text-sm leading-relaxed text-muted-foreground">
                    Sistem menormalisasi setiap nilai, menerapkan bobot, lalu membandingkan jarak prospek terhadap solusi ideal positif dan negatif.
                </p>
            </div>

            <div class="grid gap-6 px-8 py-8 md:grid-cols-2">
                @foreach([
                    ['title' => 'Normalisasi Matriks', 'formula' => 'r_{ij} = \frac{x_{ij}}{\sqrt{\sum x_{ij}^2}}'],
                    ['title' => 'Matriks Terbobot', 'formula' => 'y_{ij} = w_j \cdot r_{ij}'],
                    ['title' => 'Jarak Solusi Ideal', 'formula' => 'D_i = \sqrt{\sum (y_{ij} - y_j)^2}'],
                    ['title' => 'Nilai Preferensi', 'formula' => 'V_i = \frac{D_i^-}{D_i^- + D_i^+}'],
                ] as $formula)
                    <div class="rounded-[1.5rem] border border-border/70 bg-muted/20 p-6">
                        <div class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">{{ $formula['title'] }}</div>
                        <div class="mt-4 rounded-2xl bg-white px-5 py-4 text-center font-mono text-sm text-foreground shadow-sm">
                            {{ $formula['formula'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</x-app-layout>
