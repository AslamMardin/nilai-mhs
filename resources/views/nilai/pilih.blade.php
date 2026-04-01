@extends('layouts.app')
@section('title','Nilai')
@section('page-title','Akademik · Nilai')
@section('content')
<div class="sh">
  <h5><i class="bi bi-clipboard-data text-primary me-2"></i>Pilih Mata Kuliah — Nilai</h5>
</div>
<div class="row g-3">
  @forelse($mataKuliahList as $mk)
  @php
    $total   = $mk->mahasiswa_count;
    $dinilai = $mk->nilai_akhir_count;
    $pct     = $total > 0 ? round($dinilai/$total*100) : 0;
  @endphp
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-body">
        <code class="small text-muted">{{ $mk->kode }}</code>
        <h6 class="fw-600 mt-1 mb-1">{{ $mk->nama }}</h6>
        <div class="text-muted small mb-2">{{ $mk->kelas->nama }} · {{ $mk->dosen??'—' }}</div>
        <div class="d-flex gap-2 mb-3 flex-wrap">
          <span class="badge {{ $mk->jenis=='teori'?'bg-info text-dark':($mk->jenis=='praktikum'?'bg-success':'bg-primary') }}" style="font-size:11px">{{ $mk->label_jenis }}</span>
          <span class="badge bg-light text-dark border" style="font-size:11px">{{ $mk->sks }} SKS</span>
          <span class="badge bg-secondary" style="font-size:11px">{{ $total }} mhs</span>
        </div>
        <div class="d-flex justify-content-between small text-muted mb-1">
          <span>Progress Penilaian</span><span>{{ $dinilai }}/{{ $total }}</span>
        </div>
        <div class="progress mb-3" style="height:6px;border-radius:4px">
          <div class="progress-bar {{ $pct==100?'bg-success':($pct>0?'bg-warning':'bg-light') }}" style="width:{{ $pct }}%"></div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
          <a href="{{ route('nilai.index',$mk->id) }}" class="btn btn-sm btn-primary flex-grow-1"><i class="bi bi-eye me-1"></i>Lihat Nilai</a>
          @if($mk->hasTeori())
          <a href="{{ route('nilai.form-teori',$mk->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> Teori</a>
          @endif
          @if($mk->hasPraktikum())
          <a href="{{ route('nilai.form-praktikum',$mk->id) }}" class="btn btn-sm btn-outline-success"><i class="bi bi-tools"></i> Prak</a>
          @endif
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12"><div class="alert alert-info">Belum ada mata kuliah. <a href="{{ route('matakuliah.create') }}">Tambah mata kuliah</a>.</div></div>
  @endforelse
</div>
@endsection
