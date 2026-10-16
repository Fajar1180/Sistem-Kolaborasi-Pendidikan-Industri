<x-guest-layout>
    <x-auth-card>
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-gray-900">Daftar Sebagai Mitra Industri</h2>
            <p class="text-sm text-gray-600 mt-2">
                Buat akun untuk perwakilan perusahaan industri
            </p>
        </div>

        <form method="POST" action="{{ route('register-mitra') }}">
            @csrf

            <!-- Kontak Person -->
            <div class="mb-4">
                <x-input-label for="name" value="Nama Kontak Person" required />
                <x-text-input id="name" name="name" type="text" placeholder="Nama lengkap" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Nama Perusahaan -->
            <div class="mb-4">
                <x-input-label for="company" value="Nama Perusahaan" required />
                <x-text-input id="company" name="company" type="text" placeholder="PT. Nama Perusahaan" :value="old('company')" required />
                <x-input-error :messages="$errors->get('company')" class="mt-1" />
            </div>

            <!-- Email Perusahaan -->
            <div class="mb-4">
                <x-input-label for="email" value="Email Perusahaan" required />
                <x-text-input id="email" name="email" type="email" placeholder="email@perusahaan.com" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Nomor Telepon -->
            <div class="mb-4">
                <x-input-label for="phone" value="Nomor Telepon" required />
                <x-text-input id="phone" name="phone" type="tel" placeholder="081234567890" :value="old('phone')" required />
                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>

            <!-- Password -->
            <div class="mb-4">
                <x-input-label for="password" value="Password" required />
                <x-text-input id="password" name="password" type="password" placeholder="Minimal 8 karakter" required />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <x-input-label for="password_confirmation" value="Konfirmasi Password" required />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi password" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <!-- Submit Button -->
            <div class="mb-4">
                <x-primary-button type="submit" class="w-full">
                    Daftar Akun Mitra
                </x-primary-button>
            </div>

            <!-- Terms -->
            <div class="text-sm text-gray-600 mb-6 text-center">
                Dengan mendaftar, Anda menyetujui bahwa akun harus disetujui admin terlebih dahulu sebelum dapat login.
            </div>

            <!-- Link to Login -->
            <div class="text-center text-sm">
                <span class="text-gray-600">Sudah punya akun?</span>
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium ml-1">Login</a>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
