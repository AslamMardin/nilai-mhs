@extends('layouts.app')
@section('title','Mahasiswa')
@section('page-title','Master Data · Mahasiswa')
@section('content')
<div class="sh">
  <h5><i class="bi bi-people text-primary me-2"></i>Daftar Mahasiswa</h5>
  <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
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
          <option value="{{ $k->id }}" {{ $kampusId==$k->id?'selected':'' }}>{{ $k->kode }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label mb-1 small">Kelas</label>
        <select name="kelas_id" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">Semua Kelas</option>
          @foreach($kelasList as $kls)
          <option value="{{ $kls->id }}" {{ $kelasId==$kls->id?'selected':'' }}>{{ $kls->kode }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label mb-1 small">Cari</label>
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Nama / NIM..." value="{{ $search }}">
      </div>
      <div class="col-md-2">
        <button class="btn btn-sm btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Cari</button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr><th class="ps-3">#</th><th>NIM</th><th>Nama</th><th>Kampus / Kelas</th><th>JK</th><th>Status</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        @forelse($mahasiswa as $i => $mhs)
        <tr>
          <td class="ps-3 text-muted">{{ $mahasiswa->firstItem()+$i }}</td>
          <td><code class="small">{{ $mhs->nim }}</code></td>
          <td>{{ $mhs->nama }}</td>
          <td>
            <span class="badge bg-light text-dark border">{{ $mhs->kampus->kode }}</span>
            <span class="text-muted small ms-1">{{ $mhs->kelas->nama }}</span>
          </td>
          <td>{{ $mhs->jenis_kelamin=='L' ? 'L':'P' }}</td>
          <td>
            <span class="badge {{ match($mhs->status){'aktif'=>'bg-success','cuti'=>'bg-warning text-dark','lulus'=>'bg-primary','dropout'=>'bg-danger',default=>'bg-secondary'} }}">
              {{ ucfirst($mhs->status) }}
            </span>
          </td>
          <td>
            <a href="{{ route('mahasiswa.show',$mhs->id) }}" class="btn btn-sm btn-outline-info py-0 px-2"><i class="bi bi-eye"></i></a>
            <a href="{{ route('mahasiswa.edit',$mhs->id) }}" class="btn btn-sm btn-outline-warning py-0 px-2"><i class="bi bi-pencil"></i></a>
            <form method="POST" action="{{ route('mahasiswa.destroy',$mhs->id) }}" class="d-inline" onsubmit="return confirm('Hapus mahasiswa ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data mahasiswa.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($mahasiswa->hasPages())
  <div class="card-footer bg-white">{{ $mahasiswa->links() }}</div>
  @endif
</div>
@endsection
