<x-app-layout>
    <x-slot name="header">
        Tambah Sales Baru
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <x-ui.card>
            <form method="POST" action="{{ route('admin.sales.store') }}" class="space-y-6">
                @csrf
                
                <div class="space-y-4">
                    <x-ui.input name="name" label="Nama Lengkap" required :value="old('name')" />
                    <x-ui.input name="email" label="Email" type="email" required :value="old('email')" />
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-ui.input name="password" label="Password" type="password" required />
                        <x-ui.input name="password_confirmation" label="Konfirmasi Password" type="password" required />
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <x-ui.button tag="a" href="{{ route('admin.sales.index') }}" variant="secondary">Batal</x-ui.button>
                    <x-ui.button type="submit">Buat Akun Sales</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-app-layout>
