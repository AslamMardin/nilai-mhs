@extends('layouts.app')

@section('title','Daftarkan Mahasiswa')
@section('page-title','Daftarkan Mahasiswa ke Mata Kuliah')

@section('content')

<div class="sh">
  <h5>
    <i class="bi bi-person-plus text-success me-2"></i>
    {{ $mk->nama }}
  </h5>
</div>

{{-- FILTER --}}
<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="row g-2 align-items-end">

      <div class="col-md-3">
        <label class="form-label mb-1 small">Kampus</label>
        <select name="kampus_id" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">Semua Kampus</option>
          @foreach($kampusList as $k)
            <option value="{{ $k->id }}" {{ request('kampus_id')==$k->id?'selected':'' }}>
              {{ $k->kode }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label mb-1 small">Kelas</label>
        <select name="kelas_id" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">Semua Kelas</option>
          @foreach($kelasList as $kls)
            <option value="{{ $kls->id }}" {{ request('kelas_id')==$kls->id?'selected':'' }}>
              {{ $kls->kode }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label mb-1 small">Cari</label>
        <input type="text" name="search" class="form-control form-control-sm"
               placeholder="Nama / NIM..." value="{{ request('search') }}">
      </div>

      <div class="col-md-2">
        <button class="btn btn-sm btn-outline-primary w-100">
          <i class="bi bi-search"></i> Cari
        </button>
      </div>

    </form>
  </div>
</div>

{{-- FORM DAFTAR --}}
<form method="POST" action="{{ route('matakuliah.daftar.store',$mk->id) }}">
@csrf

<div class="card">
  <div class="card-body p-0">

    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th><input type="checkbox" id="checkAll"></th>
          <th>NIM</th>
          <th>Nama</th>
          <th>Kampus</th>
          <th>Kelas</th>
        </tr>
      </thead>
      <tbody>
        @forelse($mahasiswa as $mhs)
        <tr>
          <td>
            <input type="checkbox" name="ids[]" value="{{ $mhs->id }}">
          </td>
          <td><code>{{ $mhs->nim }}</code></td>
          <td>{{ $mhs->nama }}</td>
          <td>{{ $mhs->kampus->kode }}</td>
          <td>{{ $mhs->kelas->nama }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center text-muted py-4">
            Tidak ada data mahasiswa
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>

  </div>

  {{-- ACTION --}}
  <div class="card-footer d-flex gap-2 align-items-center">

    <select name="tahun_ajaran" class="form-select form-select-sm w-auto" required>
  <option value="">Tahun Ajaran</option>
  @for($y = 2024; $y <= 2040; $y++)
    <option value="{{ $y }}" {{ old('tahun_ajaran')==$y ? 'selected' : '' }}>
      {{ $y }}
    </option>
  @endfor
</select>

    <select name="semester" class="form-select form-select-sm w-auto" required>
      <option value="ganjil">Ganjil</option>
      <option value="genap">Genap</option>
    </select>

    <button class="btn btn-success btn-sm">
      <i class="bi bi-check-circle"></i> Daftarkan

      <span id="selectedCount" class="text-muted small"></span>
    </button>

  </div>

</div>

</form>

{{-- PAGINATION --}}
@if($mahasiswa->hasPages())
<div class="mt-3">
  {{ $mahasiswa->links() }}
</div>
@endif

@endsection

@push('scripts')
<script>
document.getElementById('checkAll').addEventListener('click', function () {
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => {
        cb.checked = this.checked;
    });
});


</script>
@endpush