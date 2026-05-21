@extends('layouts.app')
@section('title', 'Edit Mata Kuliah')
@section('page-title', 'Master Data · Edit Mata Kuliah')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><i class="bi bi-pencil me-1 text-warning"></i>Edit Mata Kuliah —
                    {{ $mataKuliah->kode }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('matakuliah.update', $mataKuliah->id) }}">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Kampus</label>
                            <select name="kampus_id" id="sel-kampus" class="form-select" required>
                                @foreach ($kampusList as $k)
                                    <option value="{{ $k->id }}"
                                        {{ old('kampus_id', $mataKuliah->kampus_id) == $k->id ? 'selected' : '' }}>
                                        {{ $k->kode }} — {{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <select name="kelas_id" id="sel-kelas" class="form-select" required>
                                @foreach ($kelasList as $kls)
                                    <option value="{{ $kls->id }}" data-kampus="{{ $kls->kampus_id }}"
                                        {{ old('kelas_id', $mataKuliah->kelas_id) == $kls->id ? 'selected' : '' }}>
                                        {{ $kls->kode }} — {{ $kls->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-4"><label class="form-label">Kode</label><input type="text" name="kode"
                                    class="form-control @error('kode') is-invalid @enderror"
                                    value="{{ old('kode', $mataKuliah->kode) }}" required>
                                @error('kode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-8"><label class="form-label">Nama</label><input type="text" name="nama"
                                    class="form-control" value="{{ old('nama', $mataKuliah->nama) }}" required></div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-4">
                                <label class="form-label">SKS</label>
                                <select name="sks" class="form-select">
                                    @foreach ([1, 2, 3, 4, 5, 6] as $s)
                                        <option value="{{ $s }}"
                                            {{ old('sks', $mataKuliah->sks) == $s ? 'selected' : '' }}>{{ $s }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label">Jenis</label>
                                <select name="jenis" class="form-select">
                                    <option value="teori" {{ old('jenis', $mataKuliah->jenis) == 'teori' ? 'selected' : '' }}>
                                        Teori</option>
                                    <option value="praktikum"
                                        {{ old('jenis', $mataKuliah->jenis) == 'praktikum' ? 'selected' : '' }}>Praktikum</option>
                                    <option value="teori_praktikum"
                                        {{ old('jenis', $mataKuliah->jenis) == 'teori_praktikum' ? 'selected' : '' }}>Teori +
                                        Praktikum</option>
                                </select>
                            </div>
                            <div class="col-4"><label class="form-label">Pertemuan</label><input type="number"
                                    name="total_pertemuan" class="form-control"
                                    value="{{ old('total_pertemuan', $mataKuliah->total_pertemuan) }}" min="1"
                                    max="16"></div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-4">
                                <label class="form-label">Tanggal Mulai Kuliah</label>
                                <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', $mataKuliah->tanggal_mulai) }}">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai', $mataKuliah->jam_mulai ? substr($mataKuliah->jam_mulai, 0, 5) : '') }}">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Jam Selesai</label>
                                <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai', $mataKuliah->jam_selesai ? substr($mataKuliah->jam_selesai, 0, 5) : '') }}">
                            </div>
                        </div>
                        <div class="mb-4"><label class="form-label">Dosen</label><input type="text" name="dosen"
                                class="form-control" value="{{ old('dosen', $mataKuliah->dosen) }}"></div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('matakuliah.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const sc = document.getElementById('sel-kampus'),
            sk = document.getElementById('sel-kelas');
        const ao = [...sk.options];

        function filter(kid) {
            const cur = sk.value;
            sk.innerHTML = '';
            ao.forEach(o => {
                if (!o.dataset.kampus || o.dataset.kampus == kid) sk.appendChild(o.cloneNode(true))
            });
            if (sk.querySelector(`option[value="${cur}"]`)) sk.value = cur;
        }
        sc.addEventListener('change', () => filter(sc.value));
        if (sc.value) filter(sc.value);
    </script>
@endpush
