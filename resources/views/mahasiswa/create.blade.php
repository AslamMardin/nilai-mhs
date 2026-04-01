@extends('layouts.app')
@section('title','Tambah Mahasiswa')
@section('page-title','Master Data · Tambah Mahasiswa')
@section('content')
<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card">
      <div class="card-header"><i class="bi bi-person-plus me-1 text-primary"></i>Form Tambah Mahasiswa</div>
      <div class="card-body">
        <form method="POST" action="{{ route('mahasiswa.store') }}">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Kampus <span class="text-danger">*</span></label>
              <select name="kampus_id" id="sel-kampus" class="form-select @error('kampus_id')is-invalid@enderror" required>
                <option value="">-- Pilih Kampus --</option>
                @foreach($kampusList as $k)
                <option value="{{ $k->id }}" {{ old('kampus_id',session('kampus_id'))==$k->id?'selected':'' }}>{{ $k->kode }}</option>
                @endforeach
              </select>
              @error('kampus_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Kelas <span class="text-danger">*</span></label>
              <select name="kelas_id" id="sel-kelas" class="form-select @error('kelas_id')is-invalid@enderror" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelasList as $kls)
                <option value="{{ $kls->id }}" data-kampus="{{ $kls->kampus_id }}" {{ old('kelas_id')==$kls->id?'selected':'' }}>{{ $kls->kode }} — {{ $kls->nama }}</option>
                @endforeach
              </select>
              @error('kelas_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label">NIM <span class="text-danger">*</span></label>
              <input type="text" name="nim" class="form-control @error('nim')is-invalid@enderror" value="{{ old('nim') }}" required>
              @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-8">
              <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" name="nama" class="form-control @error('nama')is-invalid@enderror" value="{{ old('nama') }}" required>
              @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
              <select name="jenis_kelamin" class="form-select" required>
                <option value="L" {{ old('jenis_kelamin')=='L'?'selected':'' }}>Laki-laki</option>
                <option value="P" {{ old('jenis_kelamin')=='P'?'selected':'' }}>Perempuan</option>
              </select>
            </div>
            <div class="col-md-8">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control @error('email')is-invalid@enderror" value="{{ old('email') }}">
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Telepon</label>
              <input type="text" name="telepon" class="form-control" value="{{ old('telepon') }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select name="status" class="form-select">
                <option value="aktif" {{ old('status','aktif')=='aktif'?'selected':'' }}>Aktif</option>
                <option value="cuti" {{ old('status')=='cuti'?'selected':'' }}>Cuti</option>
                <option value="lulus" {{ old('status')=='lulus'?'selected':'' }}>Lulus</option>
                <option value="dropout" {{ old('status')=='dropout'?'selected':'' }}>Dropout</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Tempat Lahir</label>
              <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Tanggal Lahir</label>
              <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">
            </div>
            <div class="col-12">
              <label class="form-label">Alamat</label>
              <textarea name="alamat" class="form-control" rows="2">{{ old('alamat') }}</textarea>
            </div>
          </div>
          <div class="d-flex gap-2 mt-4">
            <a href="{{ route('mahasiswa.index') }}" class="btn btn-outline-secondary">Batal</a>
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
// Filter kelas berdasarkan kampus yang dipilih
const selKampus = document.getElementById('sel-kampus');
const selKelas  = document.getElementById('sel-kelas');
const allOpts   = [...selKelas.options];

function filterKelas(kampusId) {
  selKelas.innerHTML = '<option value="">-- Pilih Kelas --</option>';
  allOpts.forEach(o => {
    if (!o.dataset.kampus || o.dataset.kampus == kampusId) selKelas.appendChild(o.cloneNode(true));
  });
}
selKampus.addEventListener('change', () => filterKelas(selKampus.value));
if (selKampus.value) filterKelas(selKampus.value);
</script>
@endpush
