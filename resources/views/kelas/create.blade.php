@extends('layouts.app')
@section('title','Tambah Kelas')
@section('page-title','Master Data · Tambah Kelas')
@section('content')
<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header"><i class="bi bi-door-open me-1 text-primary"></i>Form Tambah Kelas</div>
      <div class="card-body">
        <form method="POST" action="{{ route('kelas.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Kampus <span class="text-danger">*</span></label>
            <select name="kampus_id" class="form-select @error('kampus_id') is-invalid @enderror" required>
              <option value="">-- Pilih Kampus --</option>
              @foreach($kampusList as $k)
              <option value="{{ $k->id }}" {{ old('kampus_id',session('kampus_id'))==$k->id?'selected':'' }}>{{ $k->kode }} — {{ $k->nama }}</option>
              @endforeach
            </select>
            @error('kampus_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Nama Kelas <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Teknik Informatika A" required>
            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Kode Kelas <span class="text-danger">*</span></label>
            <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode') }}" placeholder="TI-A" required>
            @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label">Semester <span class="text-danger">*</span></label>
              <select name="semester" class="form-select @error('semester') is-invalid @enderror" required>
                <option value="ganjil" {{ old('semester')=='ganjil'?'selected':'' }}>Ganjil</option>
                <option value="genap" {{ old('semester')=='genap'?'selected':'' }}>Genap</option>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
              <input type="number" name="tahun_ajaran" class="form-control @error('tahun_ajaran') is-invalid @enderror" value="{{ old('tahun_ajaran',date('Y')) }}" min="2000" max="2099" required>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Wali Kelas</label>
            <input type="text" name="wali_kelas" class="form-control" value="{{ old('wali_kelas') }}" placeholder="Nama Dosen">
          </div>
          <div class="d-flex gap-2">
            <a href="{{ route('kelas.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
