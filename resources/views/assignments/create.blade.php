<x-layouts.app title="Buat Tugas Baru">
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('assignments.index') }}" class="text-sm font-semibold text-indigo-600">← Kembali ke daftar tugas</a>
        <h1 class="text-2xl font-extrabold text-slate-900 mt-2">Buat Tugas Baru</h1>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-200 p-4">
            <div class="flex items-center gap-2 text-rose-800 font-bold mb-1">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
                <span>Gagal membuat tugas. Periksa kesalahan berikut:</span>
            </div>
            <ul class="list-disc list-inside text-xs text-rose-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card p-6">
        <form method="POST" action="{{ route('assignments.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Tugas *</label>
                <input name="title" value="{{ old('title') }}" required placeholder="Contoh: Praktikum Membuat Landing Page" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi Ringkas *</label>
                <textarea name="description" rows="3" required placeholder="Jelaskan gambaran umum penugasan..." class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Instruksi Pengerjaan (Opsional)</label>
                <textarea name="instructions" rows="3" placeholder="Langkah-langkah terinci untuk siswa..." class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ old('instructions') }}</textarea>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kelas Tujuan *</label>
                    <select id="class_id_select" name="class_id" required onchange="filterSubjects()" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm bg-white">
                        <option value="">Pilih Kelas</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->class_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Mata Pelajaran *</label>
                    <select id="subject_id_select" name="subject_id" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm bg-white">
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}" data-class-id="{{ $s->class_id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }} ({{ $s->class->name ?? '' }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Batas Waktu *</label>
                    <input type="datetime-local" name="deadline_at" value="{{ old('deadline_at') }}" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nilai Maksimal *</label>
                    <input type="number" name="max_score" value="{{ old('max_score', 100) }}" min="1" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status Publikasi *</label>
                <select name="status" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm bg-white">
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Simpan sebagai Draf</option>
                    <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Publikasikan Langsung ke Siswa</option>
                </select>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-1">
                <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                    <input type="checkbox" name="allow_late_submission" value="1" {{ old('allow_late_submission', '1') ? 'checked' : '' }} class="h-4 w-4 rounded text-indigo-600">
                    Izinkan pengumpulan terlambat
                </label>
                <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                    <input type="checkbox" name="allow_resubmission" value="1" {{ old('allow_resubmission', '1') ? 'checked' : '' }} class="h-4 w-4 rounded text-indigo-600">
                    Izinkan pengumpulan ulang
                </label>
            </div>

            <div class="pt-4 border-t flex justify-end gap-3">
                <a href="{{ route('assignments.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan Tugas</button>
            </div>
        </form>
    </div>
</div>

<script>
    function filterSubjects() {
        const cSel = document.getElementById('class_id_select');
        const sSel = document.getElementById('subject_id_select');
        if (!cSel || !sSel) return;
        const cId = cSel.value;
        const opts = sSel.querySelectorAll('option[data-class-id]');
        let ok = false;
        opts.forEach(o => {
            const cid = o.getAttribute('data-class-id');
            if (!cId || cid === cId) {
                o.style.display = '';
                if (o.selected) ok = true;
            } else {
                o.style.display = 'none';
                if (o.selected) o.selected = false;
            }
        });
        if (!ok && sSel.value !== '') sSel.value = '';
    }
    document.addEventListener('DOMContentLoaded', filterSubjects);
</script>
</x-layouts.app>
