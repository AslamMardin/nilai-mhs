@extends('layouts.app')
@section('title','Mata Kuliah')
@section('page-title','Master Data · Mata Kuliah')
@section('content')
<div class="sh">
  <h5><i class="bi bi-book text-primary me-2"></i>Daftar Mata Kuliah</h5>
  <a href="{{ route('matakuliah.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
{{-- Filter --}}
<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label mb-1 small">Kampus</label>
        <select name="kampus_id" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">Semua Kampus</option>
          @foreach($kampusList as $k)
          <option value="{{ $k->id }}" {{ $filterKampus==$k->id?'selected':'' }}>{{ $k->kode }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label mb-1 small">Kelas</label>
        <select name="kelas_id" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">Semua Kelas</option>
          @foreach($kelasList as $kls)
          <option value="{{ $kls->id }}" {{ $filterKelas==$kls->id?'selected':'' }}>{{ $kls->kode }}</option>
          @endforeach
        </select>
      </div>
    </form>
  </div>
</div>
<div class="card">
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr><th class="ps-3">Kode</th><th>Nama Mata Kuliah</th><th>Kelas</th><th class="text-center">SKS</th><th class="text-center">Jenis</th><th class="text-center">Pertemuan</th><th class="text-center">Mahasiswa</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        @forelse($mataKuliah as $mk)
        <tr>
          <td class="ps-3"><code class="small">{{ $mk->kode }}</code></td>
          <td>
            <div class="fw-500">{{ $mk->nama }}</div>
            <div class="text-muted small">{{ $mk->dosen ?? '—' }}</div>
          </td>
          <td>
            <span class="badge bg-light text-dark border" style="font-size:11px">{{ $mk->kampus->kode }}</span>
            <span class="small ms-1">{{ $mk->kelas->nama }}</span>
          </td>
          <td class="text-center">{{ $mk->sks }}</td>
          <td class="text-center">
            <span class="badge {{ $mk->jenis=='teori'?'bg-info text-dark':($mk->jenis=='praktikum'?'bg-success':'bg-primary') }}" style="font-size:11px">
              {{ $mk->label_jenis }}
            </span>
          </td>
          <td class="text-center">{{ $mk->total_pertemuan }}</td>
          <td class="text-center">{{ $mk->mahasiswa_count }}</td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('nilai.index',$mk->id) }}" class="btn btn-sm btn-outline-primary py-0 px-2" title="Nilai"><i class="bi bi-clipboard-data"></i></a>
              <a href="{{ route('absensi.index',$mk->id) }}" class="btn btn-sm btn-outline-secondary py-0 px-2" title="Absensi"><i class="bi bi-calendar-check"></i></a>
              <a href="{{ route('matakuliah.edit',$mk->id) }}" class="btn btn-sm btn-outline-warning py-0 px-2"><i class="bi bi-pencil"></i></a>
              <form method="POST" action="{{ route('matakuliah.destroy',$mk->id) }}" onsubmit="return confirm('Hapus mata kuliah ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada mata kuliah.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
