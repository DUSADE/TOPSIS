<x-app-layout>
    @php
        $selectedEvaluations = $prospect->evaluations->keyBy('criteria_id');
        $completedEvaluations = $prospect->evaluations->whereNotNull('sub_criteria_id')->count();
        $totalCriteria = $criterias->count();
        $completionRate = $totalCriteria > 0 ? round(($completedEvaluations / $totalCriteria) * 100) : 0;
        $orientation = $prospect->metadata['preferred_orientation'] ?? null;
        $orientationLabels = [
            'TIMUR' => 'Timur',
            'BARAT' => 'Barat',
            'SELATAN' => 'Selatan',
            'UTARA' => 'Utara',
        ];
    @endphp

    <div class="mb-4 hidden items-center space-x-2 text-sm text-muted-foreground sm:flex">
        <a href="{{ route('prospects.index') }}" class="hover:text-foreground">{{ Auth::user()->role === 'pimpinan' ? 'Data Pelanggan' : 'Data Prospek' }}</a>
        <span>/</span>
        <span class="font-medium text-foreground">{{ $prospect->name }}</span>
    </div>

    <div class="mb-5 flex flex-wrap items-center gap-2 sm:hidden">
        <x-ui.badge color="primary">{{ $prospect->status }}</x-ui.badge>
        @if(!is_null($prospect->spk_score))
            <span class="inline-flex items-center rounded-full bg-accent/10 px-3 py-1 text-xs font-bold text-accent">
                Skor {{ number_format($prospect->spk_score, 4) }}
            </span>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <div class="space-y-8 lg:sticky lg:top-24 lg:self-start">
            <x-ui.card class="overflow-hidden border-none shadow-glass">
                <div class="relative -m-6 mb-0 overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(214,84,65,0.18),_transparent_42%),linear-gradient(135deg,_rgba(255,247,237,1),_rgba(255,255,255,1))] p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-foreground text-2xl font-bold text-white shadow-lg">
                            {{ substr($prospect->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <p class="text-[11px] font-mono uppercase tracking-[0.28em] text-muted-foreground">Prospect Profile</p>
                            <h2 class="mt-2 text-2xl font-display font-semibold text-foreground">{{ $prospect->name }}</h2>
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <x-ui.badge color="primary">{{ $prospect->status }}</x-ui.badge>
                                <span class="text-xs text-muted-foreground">Dibuat {{ $prospect->created_at->format('d M Y') }}</span>
                            </div>
                        </div>

                        @if(Auth::user()->role === 'sales')
                            <div x-data="{ open: false }">
                                <x-ui.button size="sm" variant="ghost" @click="open = true">
                                    <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </x-ui.button>

                                <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                    <div class="flex min-h-screen items-center justify-center px-4 py-8">
                                <div class="fixed inset-0 bg-slate-950/50 backdrop-blur-sm" @click="open = false"></div>
                                <div class="relative w-full max-w-2xl rounded-[1.75rem] bg-white shadow-2xl">
                                            <form method="POST" action="{{ route('prospects.update', $prospect) }}" class="p-6 md:p-8">
                                                @csrf
                                                @method('PUT')

                                                <div class="mb-6">
                                                    <p class="text-[11px] font-mono uppercase tracking-[0.28em] text-muted-foreground">Edit Prospect</p>
                                                    <h3 class="mt-2 text-2xl font-display font-semibold text-foreground">Perbarui profil dan preferensi prospek</h3>
                                                </div>

                                                <div class="grid gap-4 md:grid-cols-2">
                                                    <x-ui.input name="name" label="Nama Lengkap" value="{{ $prospect->name }}" required />
                                                    <x-ui.input name="phone" label="No. Telepon / WA" value="{{ $prospect->phone }}" required />
                                                    <x-ui.input name="email" label="Email" type="email" value="{{ $prospect->email }}" />

                                                    <div class="space-y-1.5">
                                                        <label class="block text-sm font-semibold tracking-tight text-foreground/80">Arah Unit yang Diminati</label>
                                                        <select name="preferred_orientation" class="block w-full rounded-xl border-border bg-white text-foreground shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 border-2 transition-all">
                                                            <option value="">Pilih orientasi unit</option>
                                                            @foreach($orientationLabels as $value => $label)
                                                                <option value="{{ $value }}" {{ ($orientation === $value) ? 'selected' : '' }}>{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="space-y-1.5 md:col-span-2">
                                                        <label class="block text-sm font-semibold tracking-tight text-foreground/80">Status</label>
                                                        <select name="status" class="block w-full rounded-xl border-border bg-white text-foreground shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 border-2 transition-all">
                                                            @foreach(['NEW', 'CONTACTED', 'QUALIFIED', 'LOST', 'WON'] as $status)
                                                                <option value="{{ $status }}" {{ $prospect->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mt-4">
                                                    <x-ui.textarea name="notes" label="Catatan">{{ $prospect->metadata['notes'] ?? '' }}</x-ui.textarea>
                                                </div>

                                                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                                                    <x-ui.button type="button" variant="secondary" @click="open = false">Batal</x-ui.button>
                                                    <x-ui.button type="submit">Simpan Perubahan</x-ui.button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="rounded-2xl border border-border/70 bg-muted/40 p-4">
                            <div class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Kontak</div>
                            <div class="mt-2 text-base font-semibold text-foreground">{{ $prospect->phone }}</div>
                            <div class="text-sm text-muted-foreground">{{ $prospect->email ?? '-' }}</div>
                        </div>

                        <div class="rounded-2xl border border-border/70 bg-muted/40 p-4">
                            <div class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Orientasi Unit</div>
                            <div class="mt-2 text-base font-semibold text-foreground">{{ $orientationLabels[$orientation] ?? 'Belum diisi' }}</div>
                            <div class="text-sm text-muted-foreground">Preferensi arah bangunan terkait paparan matahari pagi.</div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-primary/10 bg-primary/5 p-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <div class="text-[11px] font-mono uppercase tracking-[0.24em] text-primary">Kelengkapan Evaluasi</div>
                                <div class="mt-2 text-2xl font-display font-semibold text-foreground">{{ $completedEvaluations }}/{{ $totalCriteria }}</div>
                                <p class="mt-1 text-sm text-muted-foreground">Prospek baru masuk ranking setelah seluruh kriteria aktif terisi.</p>
                            </div>
                            <div class="h-16 w-16 rounded-full border-4 border-white bg-white text-center leading-[3.4rem] font-display text-xl font-semibold text-primary shadow-md">
                                {{ $completionRate }}%
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-border/70 bg-white p-4">
                        <div class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Skor TOPSIS</div>
                        @if(!is_null($prospect->spk_score))
                            <div class="mt-2 flex items-end justify-between gap-4">
                                <span class="text-4xl font-display font-semibold text-primary">{{ number_format($prospect->spk_score, 4) }}</span>
                                <x-ui.badge color="{{ $prospect->spk_score >= 0.75 ? 'green' : ($prospect->spk_score >= 0.55 ? 'blue' : 'gray') }}">
                                    {{ $prospect->spk_score >= 0.75 ? 'Prioritas Tinggi' : ($prospect->spk_score >= 0.55 ? 'Prioritas Menengah' : 'Perlu Nurturing') }}
                                </x-ui.badge>
                            </div>
                            <p class="mt-2 text-sm text-muted-foreground">Nilai dihitung dari delapan kriteria buyer readiness yang aktif.</p>
                        @else
                            <div class="mt-2 text-xl font-display font-semibold text-foreground">Belum tersedia</div>
                            <p class="mt-2 text-sm text-muted-foreground">Isi semua kriteria evaluasi terlebih dahulu agar prospek ikut diranking.</p>
                        @endif
                    </div>

                    @if(isset($prospect->metadata['notes']) && $prospect->metadata['notes'])
                        <div class="rounded-2xl border border-dashed border-border bg-muted/20 p-4">
                            <div class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Catatan Sales</div>
                            <p class="mt-2 text-sm leading-relaxed text-foreground/80">{{ $prospect->metadata['notes'] }}</p>
                        </div>
                    @endif
                </div>
            </x-ui.card>

            @if(Auth::user()->role === 'sales')
                <x-ui.card x-data="{ callModal: false }" class="border-none shadow-glass">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Quick Actions</p>
                            <h3 class="mt-2 text-xl font-display font-semibold text-foreground">Tindak lanjut komunikasi</h3>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <x-ui.button class="w-full justify-center" variant="secondary" @click="callModal = true">Catat Interaksi</x-ui.button>

                        <form id="wa-form" method="POST" action="{{ route('prospects.update', $prospect) }}" class="hidden">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="CONTACTED">
                            <input type="hidden" name="preferred_orientation" value="{{ $orientation }}">
                        </form>

                        <x-ui.button class="w-full justify-center" variant="primary" tag="a"
                            href="https://wa.me/{{ preg_replace('/^0/', '62', $prospect->phone) }}"
                            target="_blank"
                            onclick="if('{{ $prospect->status }}' === 'NEW') { document.getElementById('wa-form').submit(); }">
                            Kirim WhatsApp
                        </x-ui.button>
                    </div>

                    <div x-show="callModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                        <div class="flex min-h-screen items-center justify-center px-4 py-8">
                            <div class="fixed inset-0 bg-slate-950/50 backdrop-blur-sm" @click="callModal = false"></div>
                            <div class="relative w-full max-w-xl rounded-[1.75rem] bg-white p-6 shadow-2xl">
                                <form method="POST" action="{{ route('prospects.update', $prospect) }}">
                                    @csrf
                                    @method('PUT')

                                    <p class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Call Notes</p>
                                    <h3 class="mt-2 text-2xl font-display font-semibold text-foreground">Catat hasil interaksi</h3>

                                    <input type="hidden" name="preferred_orientation" value="{{ $orientation }}">

                                    <div class="mt-6">
                                        <label class="mb-2 block text-sm font-semibold text-foreground/80">Update Status Prospek</label>
                                        <div class="grid grid-cols-3 gap-2">
                                            @foreach(['CONTACTED' => 'Dihubungi', 'LOST' => 'Tidak Lanjut', 'WON' => 'Sudah Transaksi'] as $value => $label)
                                                <label class="relative flex cursor-pointer">
                                                    <input type="radio" name="status" value="{{ $value }}" class="peer sr-only" {{ $prospect->status === $value ? 'checked' : '' }}>
                                                    <div class="w-full rounded-2xl border-2 border-border bg-white px-3 py-3 text-center text-xs font-semibold text-foreground/80 transition-all peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white">
                                                        {{ $label }}
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <x-ui.textarea name="notes" label="Catatan Interaksi">{{ $prospect->metadata['notes'] ?? '' }}</x-ui.textarea>
                                    </div>

                                    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                                        <x-ui.button type="button" variant="secondary" @click="callModal = false">Batal</x-ui.button>
                                        <x-ui.button type="submit">Simpan Catatan</x-ui.button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </x-ui.card>
            @endif
        </div>

        <div class="lg:col-span-2">
            <x-ui.card class="border-none shadow-glass">
                <div class="border-b border-border/60 pb-5">
                    <p class="text-[11px] font-mono uppercase tracking-[0.28em] text-muted-foreground">SPK Evaluation</p>
                    <div class="mt-3 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                        <div>
                            <h3 class="text-2xl font-display font-semibold text-foreground">Buyer Readiness Matrix</h3>
                            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-muted-foreground">
                                Pilih satu rubrik untuk setiap kriteria. Sistem hanya menghitung ranking ketika seluruh penilaian aktif sudah lengkap.
                                <a href="{{ route('guide.index') }}" target="_blank" class="font-semibold text-primary underline-offset-4 hover:underline">Buka panduan penilaian</a>
                            </p>
                        </div>
                        <div class="rounded-2xl border border-border/70 bg-muted/30 px-4 py-3 text-sm text-muted-foreground">
                            Bobot total aktif:
                            <span class="font-semibold text-foreground">{{ number_format($criterias->sum('weight') * 100, 0) }}%</span>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('prospects.evaluations.store', $prospect) }}" class="mt-8">
                    @csrf

                    <div class="space-y-6">
                        @foreach($criterias as $criteria)
                            @php
                                $selected = $selectedEvaluations->get($criteria->id)?->sub_criteria_id;
                            @endphp
                            <section class="rounded-[1.5rem] border border-border/70 bg-[linear-gradient(180deg,_rgba(255,255,255,1),_rgba(250,245,241,0.85))] p-4 shadow-sm sm:rounded-[1.75rem] sm:p-5">
                                <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                    <div>
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex rounded-full bg-primary/10 px-3 py-1 text-[11px] font-mono uppercase tracking-[0.24em] text-primary">{{ $criteria->code }}</span>
                                            <span class="text-xs uppercase tracking-[0.18em] text-muted-foreground">{{ $criteria->type }}</span>
                                        </div>
                                        <h4 class="mt-3 text-xl font-display font-semibold text-foreground">{{ $criteria->name }}</h4>
                                    </div>
                                    <div class="rounded-2xl border border-border/70 bg-white px-4 py-3 text-right">
                                        <div class="text-[11px] font-mono uppercase tracking-[0.24em] text-muted-foreground">Bobot</div>
                                        <div class="mt-1 text-lg font-semibold text-foreground">{{ number_format($criteria->weight * 100, 0) }}%</div>
                                    </div>
                                </div>

                                <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                                    @foreach($criteria->subCriterias as $sub)
                                        <label class="relative flex cursor-pointer">
                                            <input
                                                type="radio"
                                                name="evaluations[{{ $criteria->id }}]"
                                                value="{{ $sub->id }}"
                                                class="peer sr-only"
                                                {{ $selected == $sub->id ? 'checked' : '' }}
                                                required
                                                @disabled(Auth::user()->role !== 'sales')
                                            >
                                            <div class="w-full rounded-2xl border border-border bg-white p-4 transition-all hover:border-primary/50 hover:shadow-md peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div class="text-sm font-semibold leading-relaxed">{{ $sub->label }}</div>
                                                    <span class="rounded-full bg-muted px-2.5 py-1 text-[11px] font-mono uppercase tracking-[0.16em] text-muted-foreground">
                                                        {{ number_format($sub->value, 0) }}
                                                    </span>
                                                </div>
                                                <p class="mt-3 text-xs text-muted-foreground">Semakin tinggi nilai, semakin kuat kontribusinya terhadap kesiapan prospek.</p>
                                            </div>
                                            <div class="absolute right-3 top-3 opacity-0 transition-opacity peer-checked:opacity-100">
                                                <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>

                    @if(Auth::user()->role === 'sales')
                        <div class="sticky bottom-4 mt-8 flex flex-col gap-3 rounded-[1.5rem] border border-primary/10 bg-primary/95 p-4 text-white shadow-2xl backdrop-blur md:static md:rounded-[1.75rem] md:bg-primary/5 md:p-5 md:text-inherit md:shadow-none md:backdrop-blur-0 md:flex-row md:items-center md:justify-between">
                            <p class="text-sm text-white/80 md:text-muted-foreground">Setelah disimpan, sistem menghitung ulang ranking untuk seluruh prospek yang sudah memiliki evaluasi lengkap.</p>
                            <x-ui.button type="submit" size="lg" class="justify-center md:justify-start">Simpan Evaluasi</x-ui.button>
                        </div>
                    @endif
                </form>
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
