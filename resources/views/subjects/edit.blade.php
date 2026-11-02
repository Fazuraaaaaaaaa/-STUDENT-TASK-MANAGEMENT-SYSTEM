<x-layouts.app title="Edit Mata Pelajaran">
<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('subjects.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">
            ← Kembali ke mata pelajaran
        </a>
        <h1 class="text-2xl font-extrabold text-slate-900 mt-2">Edit Mata Pelajaran</h1>
    </div>

    <div class="card p-6">
        <form method="POST" action="{{ route('subjects.update', $subject) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Mata Pelajaran *</label>
                <input name="name" value="{{ old('name', $subject->name) }}" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kode Mata Pelajaran</label>
                <input name="subject_code" value="{{ old('subject_code', $subject->subject_code) }}" placeholder="Contoh: WEB, MTK" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kelas Terkait *</label>
                <select name="class_id" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm">
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ (old('class_id', $subject->class_id) == $class->id) ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm">{{ old('description', $subject->description) }}</textarea>
            </div>
            <div class="flex items-center justify-between pt-2">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>

        <div class="border-t border-slate-100 mt-6 pt-4 flex justify-between items-center">
            <span class="text-xs text-slate-400">Hapus mata pelajaran ini jika tidak lagi digunakan</span>
            <form method="POST" action="{{ route('subjects.destroy', $subject) }}" onsubmit="return confirm('Yakin ingin menghapus mata pelajaran ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-800">
                    Hapus Mata Pelajaran
                </button>
            </form>
        </div>
    </div>
</div>
</x-layouts.app>
