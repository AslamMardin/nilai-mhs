@extends('layouts.app')
@section('title','Kampus')
@section('page-title','Master Data · Kampus')
@section('content')
<div class="sh">
  <h5><i class="bi bi-building text-primary me-2"></i>Daftar Kampus</h5>
  <a href="{{ route('kampus.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah Kampus</a>
</div>
<div class="row g-3">
  @forelse($kampusList as $k)
  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div>
            <span class="badge bg-primary mb-1" style="font-size:13px">{{ $k->kode }}</span>
            <h6 class="mb-0 fw-600">{{ $k->nama }}</h6>
            @if($k->alamat)<div class="text-muted small mt-1"><i class="bi bi-geo-alt me-1"></i>{{ $k->alamat }}</div>@endif
            @if($k->telepon)<div class="text-muted small"><i class="bi bi-telephone me-1"></i>{{ $k->telepon }}</div>@endif
          </div>
          <div class="d-flex gap-1">
            <a href="{{ route('kampus.edit',$k->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
            <form method="POST" action="{{ route('kampus.destroy',$k->id) }}" onsubmit="return confirm('Hapus kampus ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
          </div>
        </div>
        <div class="row g-2 text-center">
          <div class="col-4">
            <div class="rounded-2 p-2" style="background:#f8fafc">
              <div class="fw-700 text-primary">{{ $k->kelas_count }}</div>
              <div class="text-muted" style="font-size:11px">Kelas</div>
            </div>
          </div>
          <div class="col-4">
            <div class="rounded-2 p-2" style="background:#f8fafc">
              <div class="fw-700 text-success">{{ $k->mahasiswa_count }}</div>
              <div class="text-muted" style="font-size:11px">Mahasiswa</div>
            </div>
          </div>
          <div class="col-4">
            <div class="rounded-2 p-2" style="background:#f8fafc">
              <div class="fw-700 text-warning">{{ $k->mata_kuliah_count }}</div>
              <div class="text-muted" style="font-size:11px">Matkul</div>
            </div>
          </div>
        </div>
        <div class="mt-3 d-flex gap-2">
          <form method="POST" action="{{ route('ganti-kampus') }}" class="flex-grow-1">
            @csrf<input type="hidden" name="kampus_id" value="{{ $k->id }}">
            <button class="btn btn-sm btn-outline-primary w-100 {{ (session('kampus_id')==$k->id)?'active':'' }}">
              <i class="bi bi-building-check me-1"></i>
              {{ session('kampus_id')==$k->id ? 'Kampus Aktif' : 'Pilih Kampus Ini' }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12"><div class="alert alert-info">Belum ada data kampus. <a href="{{ route('kampus.create') }}">Tambah sekarang</a>.</div></div>
  @endforelse
</div>
@endsection
