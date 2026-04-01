@extends('layouts.app')
@section('title','Detail Mahasiswa')
@section('page-title','Detail Mahasiswa')
@section('content')
<div class="row g-3">
  <div class="col-md-4">
    <div class="card mb-3">
      <div class="card-body text-center">
        <div style="width:72px;height:72px;border-radius:50%;background:#1a4a7a;color:#fff;font-size:28px;font-weight:700;display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
          {{ strtoupper(substr($mahasiswa->nama,0,1)) }}
        </div>
        <h6 class="fw-700 mb-1">{{ $mahasiswa->nama }}</h6>
        <code class="small text-muted">{{ $mahasiswa->nim }}</code>
        <div class="mt-2">
          <span class="badge {{ match($mahasiswa->status){'aktif'=>'bg-success','cuti'=>'bg-warning text-dark','lulus'=>'bg-primary','dropout'=>'bg-danger',default=>'bg-secondary'} }}">
            {{ ucfirst($mahasiswa->status) }}
          </span>
        </div>
        <hr>
        <table class="table table-sm table-borderless text-start mb-0">
          <tr><td class="text-muted small">Kampus</td><td class="small fw-500">{{ $mahasiswa->kampus->kode }}</td></tr>
          <tr><td class="text-muted small">Kelas</td><td class="small fw-500">{{ $mahasiswa->kelas->nama }}</td></tr>
          <tr><td class="text-muted small">JK</td><td class="small">{{ $mahasiswa->jenis_kelamin=='L'?'Laki-laki':'Perempuan' }}</td></tr>
          @if($mahasiswa->email)<tr><td class="text-muted small">Email</td><td class="small">{{ $mahasiswa->email }}</td></tr>@endif
          @if($mahasiswa->telepon)<tr><td class="text-muted small">HP</td><td class="small">{{ $mahasiswa->telepon }}</td></tr>@endif
        </table>
        <div class="d-flex gap-2 mt-2">
          <a href="{{ route('mahasiswa.edit',$mahasiswa->id) }}" class="btn btn-sm btn-outline-warning flex-grow-1">Edit</a>
          <a href="{{ route('mahasiswa.form-daftar',$mahasiswa->id) }}" class="btn btn-sm btn-outline-primary flex-grow-1">Daftar Matkul</a>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    {{-- Mata Kuliah yang diikuti --}}
    <div class="card mb-3">
      <div class="card-header"><i class="bi bi-book me-1 text-primary"></i>Mata Kuliah Terdaftar</div>
      <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
          <thead class="table-light"><tr><th class="ps-3">Kode</th><th>Nama</th><th class="text-center">Jenis</th><th class="text-center">Status</th><th>Aksi</th></tr></thead>
          <tbody>
            @forelse($mahasiswa->mataKuliah as $mk)
            <tr>
              <td class="ps-3"><code class="small">{{ $mk->kode }}</code></td>
              <td>{{ $mk->nama }}</td>
              <td class="text-center"><span class="badge bg-info text-dark" style="font-size:10px">{{ $mk->label_jenis }}</span></td>
              <td class="text-center">
                <span class="badge {{ $mk->pivot->status=='lulus'?'bg-success':($mk->pivot->status=='aktif'?'bg-primary':'bg-danger') }}">
                  {{ ucfirst($mk->pivot->status) }}
                </span>
              </td>
              <td>
                <a href="{{ route('nilai.index',$mk->id) }}" class="btn btn-xs btn-outline-primary btn-sm py-0 px-1"><i class="bi bi-clipboard-data"></i></a>
              </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted py-3">Belum terdaftar di mata kuliah apapun.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Rekap Nilai --}}
    <div class="card">
      <div class="card-header"><i class="bi bi-bar-chart me-1 text-success"></i>Rekap Nilai Akhir</div>
      <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
          <thead class="table-light"><tr><th class="ps-3">Mata Kuliah</th><th class="text-center">NA</th><th class="text-center">Mutu</th><th class="text-center">Kehadiran</th><th class="text-center">Status</th></tr></thead>
          <tbody>
            @forelse($mahasiswa->nilaiAkhir as $na)
            <tr>
              <td class="ps-3 small">{{ $na->mataKuliah->nama }}</td>
              <td class="text-center fw-700">{{ $na->nilai_akhir }}</td>
              <td class="text-center">
                @if($na->huruf_mutu)<span class="badge badge-{{ strtolower($na->huruf_mutu) }}">{{ $na->huruf_mutu }}</span>@else—@endif
              </td>
              <td class="text-center">
                <span class="badge {{ $na->persentase_kehadiran>=75?'bg-success':'bg-danger' }}">{{ $na->persentase_kehadiran }}%</span>
              </td>
              <td class="text-center">
                <span class="badge {{ $na->status_kelulusan=='lulus'?'bg-success':'bg-danger' }}">
                  {{ $na->status_kelulusan=='lulus'?'Lulus':'TL' }}
                </span>
              </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted py-3">Belum ada nilai.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
