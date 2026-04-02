@extends('layouts.app')
@section('title','Kelas')
@section('page-title','Master Data · Kelas')
@section('content')
<div class="sh">
  <h5><i class="bi bi-door-open text-primary me-2"></i>Daftar Kelas {{session('kampus_id') }}</h5>
  <a href="{{ route('kelas.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah Kelas</a>
</div>
@foreach($kelasList as $kampusId => $kelasGrup)
@php $k = $kampusList->find($kampusId); @endphp
<div class="mb-4">
  <div class="d-flex align-items-center gap-2 mb-2">
    <span class="badge bg-primary">{{ $k?->kode ?? '?' }}</span>
    <span class="fw-600 text-muted small">{{ $k?->nama }}</span>
  </div>
  <div class="row g-3">
    @foreach($kelasGrup as $kls)
    <div class="col-md-4">
      <div class="card">
        <div class="card-body pb-2">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <span class="badge bg-light text-dark border mb-1">{{ $kls->kode }}</span>
              <div class="fw-600">{{ $kls->nama }}</div>
              <div class="text-muted small">{{ ucfirst($kls->semester) }} {{ $kls->tahun_ajaran }}</div>
              @if($kls->wali_kelas)<div class="text-muted small mt-1"><i class="bi bi-person me-1"></i>{{ $kls->wali_kelas }}</div>@endif
            </div>
            <div class="d-flex gap-1">
              <a href="{{ route('kelas.edit',$kls->id) }}" class="btn btn-sm btn-outline-secondary py-0 px-1"><i class="bi bi-pencil"></i></a>
              <form method="POST" action="{{ route('kelas.destroy',$kls->id) }}" onsubmit="return confirm('Hapus kelas ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger py-0 px-1"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </div>
          <div class="d-flex gap-3 mt-2 pt-2 border-top">
            <span class="text-muted small"><i class="bi bi-people me-1"></i>{{ $kls->mahasiswa_count }} mhs</span>
            <span class="text-muted small"><i class="bi bi-book me-1"></i>{{ $kls->mata_kuliah_count }} matkul</span>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endforeach
@if($kelasList->isEmpty())
<div class="alert alert-info">Belum ada kelas. <a href="{{ route('kelas.create') }}">Tambah sekarang</a>.</div>
@endif
@endsection
