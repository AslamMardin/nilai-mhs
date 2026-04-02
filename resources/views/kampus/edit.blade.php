@extends('layouts.app')
@section('title','Edit Kampus')
@section('page-title','Master Data · Edit Kampus')
@section('content')
<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header"><i class="bi bi-pencil me-1 text-warning"></i>Edit Kampus — {{ $kampus->kode }}</div>
      <div class="card-body">
        
        <form method="POST" action="{{ route('kampus.update',$kampus->id) }}">
          @csrf @method('PUT')
          <div class="mb-3">
            <label class="form-label">Nama Kampus <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama',$kampus->nama) }}" required>
            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Kode <span class="text-danger">*</span></label>
            <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode',$kampus->kode) }}" required>
            @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="2">{{ old('alamat',$kampus->alamat) }}</textarea>
          </div>
          <div class="mb-4">
            <label class="form-label">Telepon</label>
            <input type="text" name="telepon" class="form-control" value="{{ old('telepon',$kampus->telepon) }}">
          </div>
          <div class="d-flex gap-2">
            <a href="{{ route('kampus.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
