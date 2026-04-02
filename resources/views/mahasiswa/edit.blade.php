@extends('layouts.app')
@section('title','Edit Mahasiswa')
@section('page-title','Master Data · Edit Mahasiswa')
@section('content')
<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-pencil text-warning me-1"></i>Edit Mahasiswa — {{ $mahasiswa->nim }}</span>
        <a href="{{ route('mahasiswa.show',$mahasiswa->id) }}" class="btn btn-sm btn-outline-secondary">
          <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('mahasiswa.update',$mahasiswa->id) }}">
          @csrf @method('PUT')
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Kampus <span class="text-danger">*</span></label>
              <select name="kampus_id" id="sel-kampus" class="form-select @error('kampus_id')  is-invalid @enderror" required>
                @foreach($kampusList as $k)
                <option value="{{ $k->id }}" {{ old('kampus_id',$mahasiswa->kampus_id)==$k->id?'selected':'' }}>
                  {{ $k->kode }} — {{ $k->nama }}
                </option>
                @endforeach
              </select>
              @error('kampus_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Kelas <span class="text-danger">*</span></label>
              <select name="kelas_id" id="sel-kelas" class="form-select @error('kelas_id')  is-invalid @enderror" required>
                @foreach($kelasList as $kls)
                <option value="{{ $kls->id }}" data-kampus="{{ $kls->kampus_id }}"
                  {{ old('kelas_id',$mahasiswa->kelas_id)==$kls->id?'selected':'' }}>
                  {{ $kls->kode }} — {{ $kls->nama }}
                </option>
                @endforeach
              </select>
              @error('kelas_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label">NIM <span class="text-danger">*</span></label>
              <input type="text" name="nim" class="form-control @error('nim')  is-invalid @enderror"
                     value="{{ old('nim',$mahasiswa->nim) }}" required>
              @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-8">
              <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" name="nama" class="form-control @error('nama')  is-invalid @enderror"
                     value="{{ old('nama',$mahasiswa->nama) }}" required>
              @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
              <select name="jenis_kelamin" class="form-select" required>
                <option value="L" {{ old('jenis_kelamin',$mahasiswa->jenis_kelamin)=='L'?'selected':'' }}>Laki-laki</option>
                <option value="P" {{ old('jenis_kelamin',$mahasiswa->jenis_kelamin)=='P'?'selected':'' }}>Perempuan</option>
              </select>
            </div>
            <div class="col-md-8">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control @error('email')  is-invalid @enderror"
                     value="{{ old('email',$mahasiswa->email) }}">
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Telepon</label>
              <input type="text" name="telepon" class="form-control"
                     value="{{ old('telepon',$mahasiswa->telepon) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select name="status" class="form-select">
                @foreach(['aktif','cuti','lulus','dropout'] as $st)
                <option value="{{ $st }}" {{ old('status',$mahasiswa->status)==$st?'selected':'' }}>
                  {{ ucfirst($st) }}
                </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Tempat Lahir</label>
              <input type="text" name="tempat_lahir" class="form-control"
                     value="{{ old('tempat_lahir',$mahasiswa->tempat_lahir) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Tanggal Lahir</label>
              <input type="date" name="tanggal_lahir" class="form-control"
                     value="{{ old('tanggal_lahir',$mahasiswa->tanggal_lahir?->format('Y-m-d')) }}">
            </div>
            <div class="col-12">
              <label class="form-label">Alamat</label>
              <textarea name="alamat" class="form-control" rows="2">{{ old('alamat',$mahasiswa->alamat) }}</textarea>
            </div>
          </div>
          <div class="d-flex gap-2 mt-4">
            <a href="{{ route('mahasiswa.show',$mahasiswa->id) }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-warning text-white">
              <i class="bi bi-save me-1"></i>Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
const sc = document.getElementById('sel-kampus');
const sk = document.getElementById('sel-kelas');
const ao = [...sk.options];
function filter(kid) {
  const cur = sk.value;
  sk.innerHTML = '';
  ao.forEach(o => { if (!o.dataset.kampus || o.dataset.kampus == kid) sk.appendChild(o.cloneNode(true)); });
  if (sk.querySelector(`option[value="${cur}"]`)) sk.value = cur;
}
sc.addEventListener('change', () => filter(sc.value));
if (sc.value) filter(sc.value);
</script>
@endpush
