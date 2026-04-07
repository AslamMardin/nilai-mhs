@extends('layouts.app')

@section('title','Edit Profil')
@section('page-title','Akun · Edit Profil')

@section('content')
<div class="card">
  <div class="card-body">
    


    <form action="{{ route('profile.update') }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="name" 
          class="form-control @error('name') is-invalid @enderror"
          value="{{ old('name', $user->name) }}">
        @error('name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="namalengkap" 
          class="form-control @error('namalengkap') is-invalid @enderror"
          value="{{ old('namalengkap', $user->namalengkap) }}">
        @error('namalengkap')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" 
          class="form-control @error('email') is-invalid @enderror"
          value="{{ old('email', $user->email) }}">
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <button class="btn btn-primary">
        <i class="bi bi-save"></i> Simpan Perubahan
      </button>

    </form>
  </div>
</div>
@endsection