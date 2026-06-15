<x-app-layout>
    <x-slot name="header">
        Tambah Kriteria Baru
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <x-ui.card>
            <form method="POST" action="{{ route('criterias.store') }}" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-2 gap-4">
                    <x-ui.input name="code" label="Kode (Contoh: C1)" placeholder="C1" required />
                    <x-ui.input name="weight" label="Bobot (Contoh: 0.2)" type="number" step="0.0001" required />
                </div>

                <x-ui.input name="name" label="Nama Kriteria" placeholder="Contoh: Tingkat Minat, Kemampuan Finansial" required />

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="type" value="BENEFIT" class="w-4 h-4 text-primary border-border bg-white focus:ring-primary focus:ring-2" checked>
                            <span class="ml-2 text-sm text-foreground/80">BENEFIT (Keuntungan)</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="type" value="COST" class="w-4 h-4 text-primary border-border bg-white focus:ring-primary focus:ring-2">
                            <span class="ml-2 text-sm text-foreground/80">COST (Biaya)</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-5 h-5 rounded-lg border-border text-primary shadow-sm focus:ring-primary focus:ring-2 bg-white transition-all">
                    <label for="is_active" class="ml-2 block text-sm font-medium text-foreground/80">Status Aktif</label>
                </div>

                <div class="flex justify-end pt-4">
                    <x-ui.button type="submit">Simpan Kriteria</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-app-layout>
