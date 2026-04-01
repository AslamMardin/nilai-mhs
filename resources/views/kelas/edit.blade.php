@extends('layouts.app')
@section('title','Edit Kelas')
@section('page-title','Master Data · Edit Kelas')
@section('content')
<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header"><i class="bi bi-pencil me-1 text-warning"></i>Edit Kelas — {{ $kelas->kode }}</div>
      <div class="card-body">
        <form method="POST" action="{{ route('kelas.update',$kelas->id) }}">
          @csrf @method('PUT')
          <div class="mb-3">
            <label class="form-label">Kampus <span class="text-danger">*</span></label>
            <select name="kampus_id" class="form-select" required>
              @foreach($kampusList as $k)
              <option value="{{ $k->id }}" {{ old('kampus_id',$kelas->kampus_id)==$k->id?'selected':'' }}>{{ $k->kode }} — {{ $k->nama }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Nama Kelas <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama',$kelas->nama) }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Kode <span class="text-danger">*</span></label>
            <input type="text" name="kode" class="form-control" value="{{ old('kode',$kelas->kode) }}" required>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label">Semester</label>
              <select name="semester" class="form-select">
                <option value="ganjil" {{ old('semester',$kelas->semester)=='ganjil'?'selected':'' }}>Ganjil</option>
                <option value="genap" {{ old('semester',$kelas->semester)=='genap'?'selected':'' }}>Genap</option>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label">Tahun Ajaran</label>
              <input type="number" name="tahun_ajaran" class="form-control" value="{{ old('tahun_ajaran',$kelas->tahun_ajaran) }}">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Wali Kelas</label>
            <input type="text" name="wali_kelas" class="form-control" value="{{ old('wali_kelas',$kelas->wali_kelas) }}">
          </div>
          <div class="d-flex gap-2">
            <a href="{{ route('kelas.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
