<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('criterias.index') }}" class="hover:text-gray-900">Kriteria SPK</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">{{ $criteria->code }}</span>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Edit Criteria -->
        <div>
            <x-ui.card>
                <div class="mb-4 pb-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Edit Detail Kriteria</h3>
                </div>
                <form method="POST" action="{{ route('criterias.update', $criteria) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <x-ui.input name="name" label="Nama Kriteria" value="{{ $criteria->name }}" required />
                    <x-ui.input name="weight" label="Bobot (Desimal)" type="number" step="0.0001" value="{{ $criteria->weight }}" required />

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                        <select name="type" class="block w-full rounded-xl border-border bg-white text-foreground shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 border-2 transition-all">
                            <option value="BENEFIT" {{ $criteria->type == 'BENEFIT' ? 'selected' : '' }}>BENEFIT (Keuntungan)</option>
                            <option value="COST" {{ $criteria->type == 'COST' ? 'selected' : '' }}>COST (Biaya)</option>
                        </select>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ $criteria->is_active ? 'checked' : '' }} class="w-5 h-5 rounded-lg border-border text-primary shadow-sm focus:ring-primary focus:ring-2 bg-white transition-all">
                        <label for="is_active" class="ml-2 block text-sm font-medium text-foreground/80">Status Aktif</label>
                    </div>

                    <div class="pt-4">
                        <x-ui.button type="submit" class="w-full">Simpan Perubahan</x-ui.button>
                    </div>
                </form>

                <div class="mt-8 pt-8 border-t border-red-100">
                     <form method="POST" action="{{ route('criterias.destroy', $criteria) }}" onsubmit="return confirm('Yakin ingin menghapus kriteria ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-500 hover:text-red-700 text-sm font-medium">Hapus Kriteria Ini</button>
                    </form>
                </div>
            </x-ui.card>
        </div>

        <!-- Right: Manage Sub-Criteria -->
        <div class="lg:col-span-2">
            <x-ui.card>
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Sub-Kriteria (Rubrik Penilaian)</h3>
                        <p class="text-sm text-gray-500">Tentukan opsi jawaban yang tersedia untuk sales saat menilai.</p>
                    </div>
                </div>

                <!-- Add New Inline Form -->
                <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-100">
                    <form method="POST" action="{{ route('criterias.sub-criterias.store', $criteria) }}" class="flex flex-col md:flex-row md:items-end gap-3">
                        @csrf
                        <div class="flex-grow">
                            <label class="text-xs font-medium text-gray-500 uppercase">Label (Contoh: "Sudah menyatakan minat serius")</label>
                            <input type="text" name="label" required class="mt-1 block w-full rounded-xl border-border bg-white text-foreground shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 border-2 transition-all sm:text-sm">
                        </div>
                        <div class="w-24">
                            <label class="text-xs font-medium text-gray-500 uppercase">Nilai (1-5)</label>
                            <input type="number" name="value" step="0.1" required class="mt-1 block w-full rounded-xl border-border bg-white text-foreground shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 border-2 transition-all sm:text-sm">
                        </div>
                         <div class="w-20">
                            <label class="text-xs font-medium text-gray-500 uppercase">Urutan</label>
                            <input type="number" name="sequence" value="0" class="mt-1 block w-full rounded-xl border-border bg-white text-foreground shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 border-2 transition-all sm:text-sm">
                        </div>
                        <x-ui.button type="submit" variant="secondary">Tambah</x-ui.button>
                    </form>
                </div>

                <!-- List -->
                <div class="overflow-hidden border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Urutan</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Label</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nilai Poin</th>
                                <th class="px-4 py-2 text-right"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($criteria->subCriterias as $sub)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-500">{{ $sub->sequence }}</td>
                                <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ $sub->label }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900 font-bold">{{ $sub->value }}</td>
                                <td class="px-4 py-2 text-right">
                                    <form method="POST" action="{{ route('criterias.sub-criterias.destroy', [$criteria, $sub]) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-400 hover:text-red-600">×</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
