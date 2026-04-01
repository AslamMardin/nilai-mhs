@extends('layouts.app')
@section('title','Detail Kelas')
@section('page-title','Master Data · Detail Kelas')
@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
  <a href="{{ route('kelas.index') }}" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Kembali
  </a>
  <a href="{{ route('kelas.edit',$kelas->id) }}" class="btn btn-sm btn-outline-warning">
    <i class="bi bi-pencil me-1"></i>Edit
  </a>
</div>

<div class="row g-3">
  {{-- Info Kelas --}}
  <div class="col-md-4">
    <div class="card mb-3">
      <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
          <div style="width:48px;height:48px;border-radius:10px;background:#1a4a7a;color:#fff;font-size:18px;font-weight:700;display:flex;align-items:center;justify-content:center">
            {{ strtoupper(substr($kelas->kode,0,2)) }}
          </div>
          <div>
            <div class="fw-700">{{ $kelas->nama }}</div>
            <code class="small text-muted">{{ $kelas->kode }}</code>
          </div>
        </div>
        <table class="table table-sm table-borderless mb-0">
          <tr>
            <td class="text-muted small" style="width:100px">Kampus</td>
            <td class="small fw-500">{{ $kelas->kampus->kode }} — {{ $kelas->kampus->nama }}</td>
          </tr>
          <tr>
            <td class="text-muted small">Semester</td>
            <td class="small">{{ ucfirst($kelas->semester) }} {{ $kelas->tahun_ajaran }}</td>
          </tr>
          <tr>
            <td class="text-muted small">Wali Kelas</td>
            <td class="small">{{ $kelas->wali_kelas ?? '—' }}</td>
          </tr>
          <tr>
            <td class="text-muted small">Mahasiswa</td>
            <td><span class="badge bg-primary">{{ $kelas->mahasiswa->count() }}</span></td>
          </tr>
          <tr>
            <td class="text-muted small">Mata Kuliah</td>
            <td><span class="badge bg-info text-dark">{{ $kelas->mataKuliah->count() }}</span></td>
          </tr>
        </table>
      </div>
    </div>

    {{-- Mata Kuliah --}}
    <div class="card">
      <div class="card-header"><i class="bi bi-book me-1 text-primary"></i>Mata Kuliah</div>
      <div class="list-group list-group-flush">
        @forelse($kelas->mataKuliah as $mk)
        <a href="{{ route('nilai.index',$mk->id) }}" class="list-group-item list-group-item-action py-2 px-3">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <code class="small">{{ $mk->kode }}</code>
              <div class="small fw-500">{{ $mk->nama }}</div>
              <span class="badge {{ $mk->jenis=='teori'?'bg-info text-dark':($mk->jenis=='praktikum'?'bg-success':'bg-primary') }}" style="font-size:10px">
                {{ $mk->label_jenis }}
              </span>
            </div>
            <span class="badge bg-light text-dark border">{{ $mk->sks }} SKS</span>
          </div>
        </a>
        @empty
        <div class="list-group-item text-muted small py-3 text-center">Belum ada mata kuliah.</div>
        @endforelse
      </div>
    </div>
  </div>

  {{-- Daftar Mahasiswa --}}
  <div class="col-md-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people me-1 text-success"></i>Daftar Mahasiswa</span>
        <a href="{{ route('mahasiswa.create') }}" class="btn btn-sm btn-outline-primary py-0">+ Tambah</a>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0" style="font-size:13px">
          <thead class="table-light">
            <tr>
              <th class="ps-3">#</th>
              <th>NIM</th>
              <th>Nama</th>
              <th class="text-center">JK</th>
              <th class="text-center">Status</th>
              <th class="text-center">Matkul</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($kelas->mahasiswa as $i => $mhs)
            <tr>
              <td class="ps-3 text-muted">{{ $i+1 }}</td>
              <td><code style="font-size:11px">{{ $mhs->nim }}</code></td>
              <td class="fw-500">{{ $mhs->nama }}</td>
              <td class="text-center">{{ $mhs->jenis_kelamin }}</td>
              <td class="text-center">
                <span class="badge {{ match($mhs->status){
                  'aktif'=>'bg-success','cuti'=>'bg-warning text-dark',
                  'lulus'=>'bg-primary','dropout'=>'bg-danger',default=>'bg-secondary'
                } }}" style="font-size:11px">{{ ucfirst($mhs->status) }}</span>
              </td>
              <td class="text-center">
                <span class="badge bg-secondary">{{ $mhs->mataKuliah->count() }}</span>
              </td>
              <td>
                <div class="d-flex gap-1">
                  <a href="{{ route('mahasiswa.show',$mhs->id) }}"
                     class="btn btn-sm btn-outline-info py-0 px-2" title="Detail">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="{{ route('mahasiswa.edit',$mhs->id) }}"
                     class="btn btn-sm btn-outline-warning py-0 px-2" title="Edit">
                    <i class="bi bi-pencil"></i>
                  </a>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-4">
                Belum ada mahasiswa di kelas ini.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
