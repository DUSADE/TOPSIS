<x-app-layout>
    <x-slot name="header">
        Tambah Prospek Baru
    </x-slot>

    <div class="mx-auto max-w-5xl space-y-6">
        <section class="sunrise-panel rounded-[1.75rem] p-5 sm:p-6">
            <div class="grid gap-5 lg:grid-cols-[1.1fr_0.9fr] lg:items-end">
                <div>
                    <p class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Prospect Intake</p>
                    <h2 class="mt-2 font-display text-3xl font-semibold leading-tight text-foreground sm:text-4xl">
                        Mulai dari profil singkat, lalu lanjutkan ke evaluasi buyer readiness.
                    </h2>
                    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-muted-foreground">
                        Isi identitas prospek, kontak utama, dan preferensi orientasi unit. Setelah tersimpan, Anda bisa langsung mengisi delapan kriteria SPK pada halaman detail.
                    </p>
                </div>

                <div class="grid gap-3 sm:grid-cols-3 lg:grid-cols-1">
                    <a href="{{ route('guide.index') }}" target="_blank" class="rounded-[1.5rem] border border-border/70 bg-white/85 p-4 transition-colors hover:border-primary/30">
                        <p class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Panduan</p>
                        <p class="mt-2 text-base font-semibold text-foreground">Buka logika SPK</p>
                        <p class="mt-1 text-sm text-muted-foreground">Lihat rubrik penilaian buyer readiness.</p>
                    </a>
                    <div class="rounded-[1.5rem] border border-border/70 bg-white/85 p-4">
                        <p class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Arah Unit</p>
                        <p class="mt-2 text-base font-semibold text-foreground">Timur, Barat, Selatan, Utara</p>
                        <p class="mt-1 text-sm text-muted-foreground">Disimpan sebagai preferensi, bukan skor ranking.</p>
                    </div>
                    <div class="rounded-[1.5rem] border border-border/70 bg-white/85 p-4">
                        <p class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Langkah Berikutnya</p>
                        <p class="mt-2 text-base font-semibold text-foreground">Evaluasi lengkap</p>
                        <p class="mt-1 text-sm text-muted-foreground">Skor muncul setelah semua kriteria diisi.</p>
                    </div>
                </div>
            </div>
        </section>

        <x-ui.card class="border-none shadow-glass">
            <form method="POST" action="{{ route('prospects.store') }}" class="space-y-6">
                @csrf

                <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
                    <div class="space-y-6">
                        <div class="rounded-[1.5rem] border border-border/70 bg-white/80 p-5">
                            <p class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Identitas</p>
                            <div class="mt-4 grid gap-5 sm:grid-cols-2">
                                <x-ui.input name="name" label="Nama Lengkap" required />
                                <x-ui.input
                                    name="phone"
                                    label="No. Telepon / WA"
                                    required
                                    inputmode="numeric"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                />
                            </div>
                            <div class="mt-5">
                                <x-ui.input name="email" label="Alamat Email (Opsional)" type="email" />
                            </div>
                        </div>

                        <div class="rounded-[1.5rem] border border-border/70 bg-white/80 p-5">
                            <p class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Orientasi Unit</p>
                            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                                @foreach(['TIMUR', 'BARAT', 'SELATAN', 'UTARA'] as $orientation)
                                    <label class="relative flex cursor-pointer">
                                        <input type="radio" name="preferred_orientation" value="{{ $orientation }}" class="peer sr-only" {{ old('preferred_orientation') === $orientation ? 'checked' : '' }}>
                                        <div class="w-full rounded-2xl border border-border bg-white px-4 py-4 text-center text-sm font-semibold text-foreground/80 transition-all peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white">
                                            {{ ucfirst(strtolower($orientation)) }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <p class="mt-3 text-xs leading-relaxed text-muted-foreground">
                                Timur biasanya menerima matahari pagi. Preferensi ini membantu sales memahami kebutuhan unit yang dicari prospek.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-[1.5rem] border border-border/70 bg-white/80 p-5">
                            <p class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Status Awal</p>
                            <div class="mt-4 grid gap-3">
                                @foreach([
                                    'NEW' => 'Prospek baru masuk dan belum ada interaksi lanjutan.',
                                    'CONTACTED' => 'Sudah pernah dihubungi dan mulai ada komunikasi.',
                                    'QUALIFIED' => 'Sudah layak masuk tahap evaluasi dan follow up aktif.',
                                ] as $status => $description)
                                    <label class="relative flex cursor-pointer">
                                        <input type="radio" name="status" value="{{ $status }}" class="peer sr-only" {{ old('status', 'NEW') === $status ? 'checked' : '' }}>
                                        <div class="w-full rounded-[1.25rem] border border-border bg-white p-4 transition-all peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white">
                                            <div class="text-sm font-semibold">{{ $status }}</div>
                                            <p class="mt-1 text-xs leading-relaxed text-muted-foreground peer-checked:text-white/80">{{ $description }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="rounded-[1.5rem] border border-primary/10 bg-primary/5 p-5">
                            <p class="text-[11px] font-mono uppercase tracking-[0.24em] text-primary">Setelah Disimpan</p>
                            <ol class="mt-4 space-y-3 text-sm text-muted-foreground">
                                <li>1. Prospek masuk ke pipeline dan bisa dibuka dari daftar.</li>
                                <li>2. Sales mengisi evaluasi delapan kriteria buyer readiness.</li>
                                <li>3. Sistem menghitung skor TOPSIS saat evaluasi sudah lengkap.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-border/60 pt-5 sm:flex-row sm:justify-end">
                    <x-ui.button tag="a" href="{{ route('prospects.index') }}" variant="secondary" class="justify-center">Kembali</x-ui.button>
                    <x-ui.button type="submit" class="justify-center">Simpan Prospek</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-app-layout>
