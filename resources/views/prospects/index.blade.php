<x-app-layout>
    <div class="space-y-6" x-data="{ showFilters: false }">
        <section class="sunrise-panel rounded-[1.75rem] p-5 sm:p-6">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Pipeline</p>
                    <h2 class="mt-2 font-display text-3xl font-semibold leading-tight text-foreground sm:text-4xl">
                        {{ Auth::user()->role === 'pimpinan' ? 'Data Pelanggan' : 'Data Prospek' }}
                    </h2>
                    <p class="mt-3 text-sm leading-relaxed text-muted-foreground">
                        Pantau kesiapan buyer, buka detail prospek, lalu tindak lanjuti berdasarkan skor TOPSIS dan status pipeline saat ini.
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <x-ui.button
                        type="button"
                        variant="secondary"
                        class="justify-center"
                        @click="showFilters = !showFilters"
                    >
                        <span x-text="showFilters ? 'Sembunyikan Filter' : 'Tampilkan Filter'"></span>
                    </x-ui.button>

                    @if(Auth::user()->role === 'sales')
                        <x-ui.button tag="a" href="{{ route('prospects.create') }}" class="justify-center">
                            + Tambah Prospek
                        </x-ui.button>
                    @endif
                </div>
            </div>
        </section>

        <x-ui.card class="border-none shadow-glass">
            <form method="GET" action="{{ route('prospects.index') }}" class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-end">
                    <x-ui.input
                        name="search"
                        label="Cari Prospek"
                        placeholder="Nama atau nomor telepon"
                        value="{{ request('search') }}"
                    />

                    <div class="grid grid-cols-2 gap-3 sm:flex sm:justify-end">
                        <x-ui.button type="submit" variant="secondary" class="justify-center">Terapkan</x-ui.button>
                        <x-ui.button tag="a" href="{{ route('prospects.index') }}" variant="ghost" class="justify-center">Reset</x-ui.button>
                    </div>
                </div>

                <div
                    x-cloak
                    x-show="showFilters || window.innerWidth >= 640"
                    x-transition
                    class="grid gap-4 border-t border-border/60 pt-4 sm:grid-cols-3"
                >
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold tracking-tight text-foreground/80">Status</label>
                        <select name="status" class="block w-full rounded-xl border-border bg-card text-foreground shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10">
                            <option value="">Semua Status</option>
                            @foreach(['NEW', 'CONTACTED', 'QUALIFIED', 'LOST', 'WON'] as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold tracking-tight text-foreground/80">Urutkan</label>
                        <select name="sort" class="block w-full rounded-xl border-border bg-card text-foreground shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10">
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                            <option value="score_tertinggi" {{ request('sort') == 'score_tertinggi' ? 'selected' : '' }}>Skor tertinggi</option>
                            <option value="score_terendah" {{ request('sort') == 'score_terendah' ? 'selected' : '' }}>Skor terendah</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold tracking-tight text-foreground/80">Jumlah Data</label>
                        <select name="per_page" class="block w-full rounded-xl border-border bg-card text-foreground shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 data</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 data</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 data</option>
                        </select>
                    </div>
                </div>
            </form>
        </x-ui.card>

        {{-- ========================= --}}
        {{-- MOBILE VIEW --}}
        {{-- ========================= --}}
        <section class="space-y-4 md:hidden">
            @forelse($prospects as $prospect)
                <a href="{{ route('prospects.show', $prospect) }}" class="block">
                    <article class="sunrise-panel rounded-[1.5rem] p-4 transition-transform duration-200 active:scale-[0.99]">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate text-base font-semibold text-foreground">{{ $prospect->name }}</p>
                                <p class="mt-1 text-sm text-muted-foreground">{{ $prospect->phone }}</p>
                                <p class="truncate text-sm text-muted-foreground">
                                    {{ $prospect->email ?: 'Email belum diisi' }}
                                </p>
                            </div>
                            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-primary/10 text-sm font-semibold text-primary">
                                {{ substr($prospect->name, 0, 1) }}
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <x-ui.badge :color="match($prospect->status) {
                                'NEW' => 'blue',
                                'WON' => 'green',
                                'LOST' => 'red',
                                default => 'gray'
                            }">
                                {{ $prospect->status }}
                            </x-ui.badge>

                            @if(!is_null($prospect->spk_score))
                                <span class="inline-flex items-center rounded-full bg-accent/10 px-3 py-1 text-xs font-bold text-accent">
                                    Skor {{ number_format($prospect->spk_score, 4) }}
                                    ({{ number_format($prospect->spk_score * 100, 2) }}%)
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-muted px-3 py-1 text-xs font-medium text-muted-foreground">
                                    Menunggu evaluasi lengkap
                                </span>
                            @endif
                        </div>

                        <div class="mt-4 flex items-center justify-between border-t border-border/60 pt-3 text-sm">
                            <span class="text-muted-foreground">Buka detail & evaluasi</span>
                            <span class="font-semibold text-primary">Lihat</span>
                        </div>
                    </article>
                </a>
            @empty
                <x-ui.card class="border-none shadow-glass">
                    <div class="py-8 text-center">
                        <p class="text-base font-semibold text-foreground">Belum ada data prospek</p>
                        <p class="mt-2 text-sm text-muted-foreground">
                            Tambahkan prospek baru untuk mulai membangun ranking buyer readiness.
                        </p>
                    </div>
                </x-ui.card>
            @endforelse
        </section>

        {{-- ========================= --}}
        {{-- DESKTOP VIEW --}}
        {{-- ========================= --}}
        <x-ui.card class="hidden border-none shadow-glass md:block">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-border/60">
                    <thead class="bg-muted/30">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground">Nama Lengkap</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground">Kontak</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground">Skor SPK / Akurasi</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-muted-foreground">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/50 bg-white">
                        @forelse($prospects as $prospect)
                            <tr
                                class="cursor-pointer transition-colors hover:bg-primary/5"
                                onclick="window.location='{{ route('prospects.show', $prospect) }}'"
                            >
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm font-medium text-foreground">
                                        {{ $prospect->name }}
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-foreground">{{ $prospect->phone }}</div>
                                    <div class="text-sm text-muted-foreground">
                                        {{ $prospect->email ?: 'Email belum diisi' }}
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    <x-ui.badge :color="match($prospect->status) {
                                        'NEW' => 'blue',
                                        'WON' => 'green',
                                        'LOST' => 'red',
                                        default => 'gray'
                                    }">
                                        {{ $prospect->status }}
                                    </x-ui.badge>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    @if(!is_null($prospect->spk_score))
                                        <div class="space-y-1">
                                            <span class="inline-flex items-center rounded-md bg-accent/10 px-2.5 py-0.5 text-sm font-bold text-accent">
                                                {{ number_format($prospect->spk_score, 4) }}
                                            </span>

                                            <div class="text-xs font-semibold text-green-600">
                                                {{ number_format($prospect->spk_score * 100, 2) }}%
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-muted-foreground">
                                            Menunggu evaluasi lengkap
                                        </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <a
                                        href="{{ route('prospects.show', $prospect) }}"
                                        class="text-primary transition-colors hover:text-primary/80"
                                    >
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-muted-foreground">
                                    Belum ada data prospek.
                                    <a href="{{ route('prospects.create') }}" class="text-primary underline">
                                        Tambah baru
                                    </a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-border/60 px-4 py-3">
                {{ $prospects->links() }}
            </div>
        </x-ui.card>

        <div class="md:hidden">
            {{ $prospects->links() }}
        </div>
    </div>
</x-app-layout>