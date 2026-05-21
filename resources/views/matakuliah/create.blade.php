@extends('layouts.app')
@section('title','Tambah Mata Kuliah')
@section('page-title','Master Data · Tambah Mata Kuliah')
@section('content')
<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header"><i class="bi bi-book-fill me-1 text-primary"></i>Form Tambah Mata Kuliah</div>
      <div class="card-body">
        <form method="POST" action="{{ route('matakuliah.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Kampus <span class="text-danger">*</span></label>
            <select name="kampus_id" id="sel-kampus" class="form-select @error('kampus_id') is-invalid @enderror" required>
              <option value="">-- Pilih Kampus --</option>
              @foreach($kampusList as $k)
              <option value="{{ $k->id }}" {{ old('kampus_id',session('kampus_id'))==$k->id?'selected':'' }}>{{ $k->kode }} — {{ $k->nama }}</option>
              @endforeach
            </select>
            @error('kampus_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Kelas <span class="text-danger">*</span></label>
            <select name="kelas_id" id="sel-kelas" class="form-select @error('kelas_id') is-invalid @enderror" required>
              <option value="">-- Pilih Kelas --</option>
              @foreach($kelasList as $kls)
              <option value="{{ $kls->id }}" data-kampus="{{ $kls->kampus_id }}" {{ old('kelas_id')==$kls->id?'selected':'' }}>{{ $kls->kode }} — {{ $kls->nama }}</option>
              @endforeach
            </select>
            @error('kelas_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="row g-3 mb-3">
            <div class="col-4">
              <label class="form-label">Kode <span class="text-danger">*</span></label>
              <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode') }}" placeholder="TI101" required>
              @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-8">
              <label class="form-label">Nama Mata Kuliah <span class="text-danger">*</span></label>
              <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
              @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-4">
              <label class="form-label">SKS <span class="text-danger">*</span></label>
              <select name="sks" class="form-select">
                @foreach([1,2,3,4,5,6] as $s)
                <option value="{{ $s }}" {{ old('sks',2)==$s?'selected':'' }}>{{ $s }} SKS</option>
                @endforeach
              </select>
            </div>
            <div class="col-4">
              <label class="form-label">Jenis <span class="text-danger">*</span></label>
              <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                <option value="teori" {{ old('jenis','teori')=='teori'?'selected':'' }}>Teori</option>
                <option value="praktikum" {{ old('jenis')=='praktikum'?'selected':'' }}>Praktikum</option>
                <option value="teori_praktikum" {{ old('jenis')=='teori_praktikum'?'selected':'' }}>Teori + Praktikum</option>
              </select>
            </div>
            <div class="col-4">
              <label class="form-label">Total Pertemuan</label>
              <input type="number" name="total_pertemuan" class="form-control" value="{{ old('total_pertemuan',16) }}" min="1" max="16">
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-4">
              <label class="form-label">Tanggal Mulai Kuliah</label>
              <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}">
            </div>
            <div class="col-4">
              <label class="form-label">Jam Mulai</label>
              <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai') }}">
            </div>
            <div class="col-4">
              <label class="form-label">Jam Selesai</label>
              <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai') }}">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Nama Dosen</label>
            <input type="text" name="dosen" class="form-control" value="{{ old('dosen') }}" placeholder="Dr. ...">
          </div>
          <div class="d-flex gap-2">
            <a href="{{ route('matakuliah.index') }}" class="btn btn-outline-secondary">Batal</a>
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
const sc=document.getElementById('sel-kampus'),sk=document.getElementById('sel-kelas');
const ao=[...sk.options];
function filter(kid){sk.innerHTML='<option value="">-- Pilih Kelas --</option>';ao.forEach(o=>{if(!o.dataset.kampus||o.dataset.kampus==kid)sk.appendChild(o.cloneNode(true))});}
sc.addEventListener('change',()=>filter(sc.value));
if(sc.value)filter(sc.value);
</script>
@endpush
