<x-app-layout>
    <x-slot name="header">
        Manajemen Akun Sales
    </x-slot>

    <div class="mb-6 flex justify-between items-center">
        <p class="text-sm text-gray-600">Daftar staf sales yang aktif di sistem.</p>
        <x-ui.button tag="a" href="{{ route('admin.sales.create') }}">
            + Tambah Sales Baru
        </x-ui.button>
    </div>

    <x-ui.card>
        <div class="overflow-x-auto" x-data="{ deleteModal: false, selectedSales: null, salesName: '' }">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Prospek</th>
                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sales as $s)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $s->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $s->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $s->prospects_count }} Data
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('admin.sales.edit', $s) }}" class="text-primary-600 hover:text-primary-900">Edit</a>
                            <button @click="deleteModal = true; selectedSales = {{ $s->id }}; salesName = '{{ $s->name }}'" class="text-red-600 hover:text-red-900">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">Belum ada data sales.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Reassign & Delete Modal -->
            <div x-show="deleteModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="deleteModal = false">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form :action="'{{ url('/admin/sales') }}/' + selectedSales" method="POST" class="p-6">
                            @csrf
                            @method('DELETE')
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Hapus Sales: <span x-text="salesName" class="text-red-600"></span></h3>
                            
                            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0 text-amber-400">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-amber-700">
                                            Menghapus sales akan menyebakan datanya menjadi yatim. Silahkan alihkan semua prospek sales ini ke sales lain.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Alihkan Prospek Ke:</label>
                                    <select name="transfer_to" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                        <option value="">-- Pilih Sales Tujuan --</option>
                                        @foreach($sales as $target)
                                            <template x-if="selectedSales != {{ $target->id }}">
                                                <option value="{{ $target->id }}">{{ $target->name }}</option>
                                            </template>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mt-8 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                <x-ui.button type="submit" variant="primary" class="w-full sm:col-start-2 bg-red-600 hover:bg-red-700 border-red-600">Hapus & Alihkan</x-ui.button>
                                <x-ui.button type="button" variant="secondary" class="w-full sm:col-start-1" @click="deleteModal = false">Batal</x-ui.button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-ui.card>
</x-app-layout>
