@csrf

<div class="grid gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label for="judul" class="block text-sm font-medium">Judul Peluang</label>
        <input id="judul" name="judul" type="text" required
               value="{{ old('judul', $program->judul ?? '') }}"
               class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
    </div>

    <div>
        <label for="jenis" class="block text-sm font-medium">Jenis</label>
        <select id="jenis" name="jenis" required class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
            @foreach (\App\Models\Program::JENIS as $jenis)
                <option value="{{ $jenis }}" @selected(old('jenis', $program->jenis ?? '') === $jenis)>
                    {{ str_replace('_', ' ', $jenis) }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="kuota" class="block text-sm font-medium">Kuota</label>
        <input id="kuota" name="kuota" type="number" min="1" required
               value="{{ old('kuota', $program->kuota ?? 1) }}"
               class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
    </div>

    <div>
        <label for="tanggal_mulai" class="block text-sm font-medium">Tanggal Mulai</label>
        <input id="tanggal_mulai" name="tanggal_mulai" type="date" required
               value="{{ old('tanggal_mulai', isset($program) ? $program->tanggal_mulai?->toDateString() : '') }}"
               class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
    </div>

    <div>
        <label for="tanggal_selesai" class="block text-sm font-medium">Tanggal Selesai</label>
        <input id="tanggal_selesai" name="tanggal_selesai" type="date" required
               value="{{ old('tanggal_selesai', isset($program) ? $program->tanggal_selesai?->toDateString() : '') }}"
               class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
    </div>
</div>

<div>
    <label for="deskripsi" class="block text-sm font-medium">Deskripsi</label>
    <textarea id="deskripsi" name="deskripsi" rows="4" required
              class="mt-1 w-full rounded border border-slate-300 px-3 py-2">{{ old('deskripsi', $program->deskripsi ?? '') }}</textarea>
</div>

<div>
    <label for="syarat" class="block text-sm font-medium">Syarat</label>
    <textarea id="syarat" name="syarat" rows="3"
              class="mt-1 w-full rounded border border-slate-300 px-3 py-2">{{ old('syarat', $program->syarat ?? '') }}</textarea>
</div>

<div class="flex gap-3">
    <button type="submit" class="rounded bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">
        {{ isset($program) ? 'Simpan Perubahan' : 'Simpan Draf' }}
    </button>
    <a href="{{ route('mitra.program.index') }}" class="rounded border border-slate-300 px-4 py-2 text-sm">Batal</a>
</div>
