<x-app-layout>
    <x-slot name="header">
        Buyer Readiness Overview
    </x-slot>

    <div class="space-y-6">
        <section class="sunrise-panel rounded-[1.75rem] p-5 sm:p-6 lg:p-7">
            <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr] lg:items-end">
                <div>
                    <p class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Overview</p>
                    <h2 class="mt-2 font-display text-3xl font-semibold leading-tight text-foreground sm:text-4xl">
                        Prioritaskan prospek dengan sinyal kesiapan yang paling kuat.
                    </h2>
                    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-muted-foreground">
                        Dashboard ini menyorot jumlah prospek, kesiapan untuk diranking, dan daftar tindak lanjut yang harus didahulukan berdasarkan skor TOPSIS terbaru.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 lg:grid-cols-1">
                    <div class="rounded-[1.5rem] border border-border/70 bg-white/85 p-4">
                        <p class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Ranking Coverage</p>
                        <p class="mt-3 text-3xl font-display font-semibold text-foreground">
                            {{ $totalProspects > 0 ? number_format(($rankedProspects / $totalProspects) * 100, 0) : 0 }}%
                        </p>
                        <p class="mt-1 text-sm text-muted-foreground">Prospek yang sudah memiliki evaluasi lengkap.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <x-ui.stat-card class="bg-card border-none shadow-glass" label="{{ Auth::user()->role === 'pimpinan' ? 'Total Pelanggan' : 'Total Prospek' }}" value="{{ $totalProspects }}" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-2m-5 7V11a2 2 0 012-2h2a2 2 0 012 2v9m-18 0V9a2 2 0 012-2h2a2 2 0 012 2v11m-6 0h4"></path></svg>' />
            <x-ui.stat-card class="bg-card border-none shadow-glass" label="Siap Diranking" value="{{ $rankedProspects }}" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>' />
            <x-ui.stat-card class="bg-card border-none shadow-glass" label="Menunggu Evaluasi Lengkap" value="{{ $pendingEvaluationProspects }}" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6 1a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <section class="space-y-4">
                <div class="flex items-end justify-between gap-3">
                    <div>
                        <x-ui.section-label class="mb-1">SPK Analysis</x-ui.section-label>
                        <h3 class="text-2xl font-display font-semibold tracking-tight text-foreground">Prioritas Tindak Lanjut</h3>
                    </div>
                    <x-ui.button tag="a" href="{{ route('prospects.index', ['sort' => 'score_tertinggi']) }}" size="sm" variant="ghost" class="hidden sm:inline-flex">
                        Lihat daftar lengkap
                    </x-ui.button>
                </div>

                <div class="space-y-4 md:hidden">
                    @forelse($topProspects as $prospect)
                        <a href="{{ route('prospects.show', $prospect) }}" class="block">
                            <article class="sunrise-panel rounded-[1.5rem] p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h4 class="truncate text-base font-semibold text-foreground">{{ $prospect->name }}</h4>
                                        <p class="mt-1 text-sm text-muted-foreground">{{ $prospect->phone ?? 'No contact' }}</p>
                                    </div>
                                    <x-ui.badge color="{{ $prospect->spk_score >= 0.75 ? 'green' : ($prospect->spk_score >= 0.55 ? 'blue' : 'gray') }}">
                                        {{ $prospect->status }}
                                    </x-ui.badge>
                                </div>
                                <div class="mt-4 flex items-center justify-between border-t border-border/60 pt-3">
                                    <div>
                                        <p class="text-[11px] font-mono uppercase tracking-[0.22em] text-muted-foreground">TOPSIS</p>
                                        <p class="mt-1 font-mono text-lg font-semibold text-primary">{{ number_format($prospect->spk_score, 4) }}</p>
                                    </div>
                                    <div class="h-2 w-24 overflow-hidden rounded-full bg-muted">
                                        <div class="h-full rounded-full bg-primary" style="width: {{ $prospect->spk_score * 100 }}%"></div>
                                    </div>
                                </div>
                            </article>
                        </a>
                    @empty
                        <x-ui.card class="border-none shadow-glass">
                            <div class="py-8 text-center text-muted-foreground">
                                Belum ada prospek dengan evaluasi lengkap.
                            </div>
                        </x-ui.card>
                    @endforelse
                </div>

                <x-ui.card class="hidden overflow-hidden border-none shadow-glass md:block">
                    <div class="overflow-x-auto -mx-6 -mb-6">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-y border-border/50 bg-muted/30">
                                    <th class="px-6 py-4 text-left font-mono text-[10px] uppercase tracking-widest text-muted-foreground">Prospect Name</th>
                                    <th class="px-6 py-4 text-left font-mono text-[10px] uppercase tracking-widest text-muted-foreground">TOPSIS Score</th>
                                    <th class="px-6 py-4 text-left font-mono text-[10px] uppercase tracking-widest text-muted-foreground">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border/30">
                                @forelse($topProspects as $prospect)
                                    <tr class="group transition-colors hover:bg-primary/5">
                                        <td class="whitespace-nowrap px-6 py-5">
                                            <a href="{{ route('prospects.show', $prospect) }}" class="font-bold text-foreground transition-colors hover:text-primary">
                                                {{ $prospect->name }}
                                            </a>
                                            <p class="mt-0.5 text-[10px] uppercase tracking-tight text-muted-foreground">{{ $prospect->phone ?? 'No contact' }}</p>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-5 font-mono text-sm">
                                            <div class="flex items-center space-x-3">
                                                <span class="font-bold text-primary">{{ number_format($prospect->spk_score, 4) }}</span>
                                                <div class="h-1 w-16 overflow-hidden rounded-full bg-muted">
                                                    <div class="h-full rounded-full bg-primary transition-all duration-1000" style="width: {{ $prospect->spk_score * 100 }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-5 text-sm">
                                            <x-ui.badge color="{{ $prospect->spk_score >= 0.75 ? 'green' : ($prospect->spk_score >= 0.55 ? 'blue' : 'gray') }}">
                                                {{ $prospect->status }}
                                            </x-ui.badge>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-10 text-center text-muted-foreground">
                                            Belum ada prospek dengan evaluasi lengkap.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-ui.card>
            </section>

            <section class="space-y-4">
                @if(isset($salesPerformance))
                    <div>
                        <x-ui.section-label class="mb-1">Performance Metrics</x-ui.section-label>
                        <h3 class="text-2xl font-display font-semibold tracking-tight text-foreground">Sales Contribution</h3>
                    </div>

                    <div class="space-y-3 md:hidden">
                        @forelse($salesPerformance as $sales)
                            <article class="sunrise-panel rounded-[1.5rem] p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex min-w-0 items-center gap-3">
                                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-2xl bg-primary/10 text-xs font-bold text-primary">
                                            {{ substr($sales->name, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-foreground">{{ $sales->name }}</p>
                                            <p class="text-sm text-muted-foreground">{{ $sales->prospects_count }} prospek</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[11px] font-mono uppercase tracking-[0.22em] text-muted-foreground">Share</p>
                                        <p class="mt-1 font-semibold text-foreground">{{ $totalProspects > 0 ? number_format(($sales->prospects_count / $totalProspects) * 100, 0) : 0 }}%</p>
                                    </div>
                                </div>
                                <div class="mt-4 h-2 overflow-hidden rounded-full bg-muted">
                                    <div class="h-full rounded-full bg-primary" style="width: {{ $totalProspects > 0 ? ($sales->prospects_count / $totalProspects) * 100 : 0 }}%"></div>
                                </div>
                            </article>
                        @empty
                            <x-ui.card class="border-none shadow-glass">
                                <div class="py-8 text-center text-muted-foreground">Belum ada data sales.</div>
                            </x-ui.card>
                        @endforelse
                    </div>

                    <x-ui.card class="hidden overflow-hidden border-none shadow-glass md:block">
                        <div class="overflow-x-auto -mx-6 -mb-6">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-y border-border/50 bg-muted/30">
                                        <th class="px-6 py-4 text-left font-mono text-[10px] uppercase tracking-widest text-muted-foreground">Team Member</th>
                                        <th class="px-6 py-4 text-left font-mono text-[10px] uppercase tracking-widest text-muted-foreground">Total Leads</th>
                                        <th class="px-6 py-4 text-left font-mono text-[10px] uppercase tracking-widest text-muted-foreground">Market Share</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border/30">
                                    @forelse($salesPerformance as $sales)
                                        <tr class="group transition-colors hover:bg-primary/5">
                                            <td class="whitespace-nowrap px-6 py-5">
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-xs font-bold text-primary">
                                                        {{ substr($sales->name, 0, 1) }}
                                                    </div>
                                                    <span class="font-bold text-foreground">{{ $sales->name }}</span>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-5">
                                                <span class="rounded bg-muted px-2 py-1 text-xs font-bold tracking-tight text-foreground">
                                                    {{ $sales->prospects_count }} Prospek
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-5">
                                                <div class="h-1.5 w-full max-w-[120px] rounded-full bg-muted">
                                                    <div class="h-full rounded-full bg-primary transition-all duration-1000" style="width: {{ $totalProspects > 0 ? ($sales->prospects_count / $totalProspects) * 100 : 0 }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-center italic text-muted-foreground">Belum ada data sales.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </x-ui.card>
                @else
                    <x-ui.card variant="glass" class="border-none shadow-glass">
                        <div class="space-y-5">
                            <div>
                                <x-ui.section-label class="mb-1">Intelligence Hub</x-ui.section-label>
                                <h3 class="text-2xl font-display font-semibold tracking-tight text-foreground">System Insights</h3>
                            </div>
                            <p class="text-sm leading-relaxed text-muted-foreground">
                                Gunakan dashboard ini untuk melihat jumlah prospek yang sudah layak diranking dan lanjutkan evaluasi pada prospek yang masih belum lengkap.
                            </p>
                            <div class="rounded-2xl border border-primary/10 bg-primary/5 p-5">
                                <div class="flex items-start gap-3">
                                    <div class="rounded-lg bg-primary/10 p-2 text-primary">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 text-xs font-bold uppercase tracking-wider text-primary">Optimization Tip</h4>
                                        <p class="text-xs leading-normal text-muted-foreground">
                                            Pastikan delapan kriteria buyer readiness sudah diisi lengkap agar prospek masuk ke ranking TOPSIS.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-ui.card>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
