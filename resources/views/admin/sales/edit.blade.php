<x-app-layout>
    <x-slot name="header">
        Edit Akun Sales: {{ $user->name }}
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <x-ui.card>
            <form method="POST" action="{{ route('admin.sales.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <x-ui.input name="name" label="Nama Lengkap" required :value="old('name', $user->name)" />
                    <x-ui.input name="email" label="Email" type="email" required :value="old('email', $user->email)" />
                    
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <p class="text-xs text-gray-500 mb-4 italic">Biarkan kosong jika tidak ingin mengganti password.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-ui.input name="password" label="Password Baru" type="password" />
                            <x-ui.input name="password_confirmation" label="Konfirmasi Password Baru" type="password" />
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <x-ui.button tag="a" href="{{ route('admin.sales.index') }}" variant="secondary">Batal</x-ui.button>
                    <x-ui.button type="submit">Simpan Perubahan</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-app-layout>
