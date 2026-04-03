@extends('layouts.app')

@section('title','Peserta Mata Kuliah')
@section('page-title','Peserta · '.$mk->nama)

@section('content')

<div class="card">
  <div class="card-body p-0">
    <div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="row g-2 align-items-end">
          <div class="col-md-3">
        <label class="form-label small">Kelas </label>
        <select name="kelas_id" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">Semua</option>
          @foreach($kelasList->kelas as $kls)
            <option value="{{ $kls->id }}" {{ request('kelas_id')==$kls->id?'selected':'' }}>
              {{ $kls->nama }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label small">Cari</label>
        <input type="text" name="search" class="form-control form-control-sm"
               placeholder="Nama / NIM..."
               value="{{ request('search') }}">
      </div>

      <div class="col-md-2">
        <button class="btn btn-sm btn-primary w-100">
          <i class="bi bi-search"></i> Cari
        </button>
      </div>

    </form>
  </div>
</div>
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>NIM</th>
          <th>Nama</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($mahasiswa as $i => $mhs)
<tr>
  <td>{{ $mahasiswa->firstItem() + $i }}</td>
  <td>{{ $mhs->nim }}</td>
  <td>{{ $mhs->nama }}</td>
          <td>
            <form method="POST" 
                  action="{{ route('matakuliah.removeMahasiswa') }}" 
                  onsubmit="return confirm('Hapus mahasiswa ini dari mata kuliah?')">
              @csrf
              @method('DELETE')

              <input type="hidden" name="mahasiswa_id" value="{{ $mhs->id }}">
              <input type="hidden" name="mata_kuliah_id" value="{{ $mk->id }}">

              <button class="btn btn-sm btn-outline-danger">
                <i class="bi bi-x"></i>
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4" class="text-center text-muted">Belum ada peserta</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">
  {{ $mahasiswa->links() }}
</div>
</div>

@endsection