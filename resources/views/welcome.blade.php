<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WSL CRM Buyer Readiness</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-background text-foreground selection:bg-primary/20">
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute left-[-8%] top-[-12%] h-[24rem] w-[24rem] rounded-full bg-primary/10 blur-3xl"></div>
        <div class="absolute right-[-6%] top-[18%] h-[22rem] w-[22rem] rounded-full bg-accent/15 blur-3xl"></div>
        <div class="absolute bottom-[-10%] left-[28%] h-[18rem] w-[18rem] rounded-full bg-secondary/10 blur-3xl"></div>
    </div>

    <nav class="mx-auto flex w-[92%] max-w-7xl items-center justify-between py-8">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-foreground text-lg font-bold text-white shadow-lg shadow-primary/20">
                W
            </div>
            <div>
                <div class="font-display text-2xl font-semibold tracking-tight">WSL CRM</div>
                <div class="text-[11px] font-mono uppercase tracking-[0.28em] text-muted-foreground">Buyer Readiness</div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            @if (Route::has('login'))
                @auth
                    <x-ui.button tag="a" href="{{ url('/dashboard') }}">Masuk Dashboard</x-ui.button>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-foreground/70 transition-colors hover:text-primary">Login</a>
                @endauth
            @endif
        </div>
    </nav>

    <main class="mx-auto w-[92%] max-w-7xl pb-20 pt-6">
        <section class="grid items-center gap-10 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="space-y-8">
                <x-ui.section-label>CRM + TOPSIS</x-ui.section-label>
                <div class="space-y-5">
                    <h1 class="max-w-4xl font-display text-5xl font-semibold leading-[0.94] tracking-tight text-foreground md:text-7xl">
                        Urutkan calon pembeli berdasarkan
                        <span class="gradient-text">kesiapan transaksi yang nyata.</span>
                    </h1>
                    <p class="max-w-2xl text-lg leading-relaxed text-muted-foreground">
                        WSL CRM membantu tim sales properti mencatat prospek, membaca sinyal kesiapan pembelian, lalu
                        memprioritaskan tindak lanjut menggunakan model TOPSIS yang selaras dengan buyer readiness.
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    @if (Route::has('login'))
                        <x-ui.button tag="a" href="{{ route('login') }}" size="lg" class="px-8">Mulai Gunakan</x-ui.button>
                    @endif
                    <x-ui.button tag="a" href="{{ route('guide.index') }}" variant="secondary-outline" size="lg" class="px-8">Lihat Panduan SPK</x-ui.button>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="sunrise-panel rounded-[1.5rem] p-5">
                        <div class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">8 Kriteria</div>
                        <div class="mt-2 text-3xl font-display font-semibold text-foreground">Buyer</div>
                        <p class="mt-2 text-sm text-muted-foreground">Berbasis minat, finansial, interaksi, dokumen, kunjungan, urgensi, DP, dan stok unit.</p>
                    </div>
                    <div class="sunrise-panel rounded-[1.5rem] p-5">
                        <div class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Live Ranking</div>
                        <div class="mt-2 text-3xl font-display font-semibold text-foreground">TOPSIS</div>
                        <p class="mt-2 text-sm text-muted-foreground">Skor diperbarui setelah evaluasi tersimpan, dengan bobot aktif yang bisa dikelola admin.</p>
                    </div>
                    <div class="sunrise-panel rounded-[1.5rem] p-5">
                        <div class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Pipeline</div>
                        <div class="mt-2 text-3xl font-display font-semibold text-foreground">Terarah</div>
                        <p class="mt-2 text-sm text-muted-foreground">Prospek yang belum lengkap tidak dipaksa masuk ranking agar prioritas tetap bersih.</p>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="sunrise-panel rounded-[2rem] p-6 md:p-8">
                    <div class="rounded-[1.75rem] bg-foreground p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-[11px] font-mono uppercase tracking-[0.24em] text-white/50">Example Readiness</div>
                                <div class="mt-2 text-5xl font-display font-semibold">0.8421</div>
                            </div>
                            <div class="rounded-full border border-white/10 bg-white/10 px-4 py-2 text-sm font-semibold text-white/80">Prioritas Tinggi</div>
                        </div>

                        <div class="mt-8 grid gap-4">
                            @foreach([
                                ['label' => 'Minat Pembeli', 'value' => '5/5'],
                                ['label' => 'Kemampuan Finansial', 'value' => '4/5'],
                                ['label' => 'Dokumen Administratif', 'value' => '4/5'],
                                ['label' => 'Kesesuaian DP', 'value' => '5/5'],
                            ] as $item)
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                    <div class="flex items-center justify-between gap-4">
                                        <span class="text-sm text-white/70">{{ $item['label'] }}</span>
                                        <span class="font-mono text-sm font-semibold">{{ $item['value'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div class="rounded-[1.5rem] border border-border/70 bg-white p-5">
                            <div class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Sales Notes</div>
                            <p class="mt-3 text-sm leading-relaxed text-foreground/80">
                                Prospek sudah survey ulang bersama keluarga, meminta simulasi KPR, dan preferensi unit timur untuk cahaya pagi.
                            </p>
                        </div>
                        <div class="rounded-[1.5rem] border border-border/70 bg-white p-5">
                            <div class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Action</div>
                            <p class="mt-3 text-sm leading-relaxed text-foreground/80">
                                Jadwalkan follow up pembiayaan dan kunci unit yang tersedia sebelum dialihkan ke prospek lain.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
