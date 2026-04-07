@extends('layouts.app')

@section('title','Ganti Password')
@section('page-title','Akun · Ganti Password')

@section('content')
<div class="card">
  <div class="card-body">



    <form action="{{ route('password.update') }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Password Lama</label>
        <input type="password" name="current_password"
          class="form-control @error('current_password') is-invalid @enderror">
        @error('current_password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Password Baru</label>
        <input type="password" name="password"
          class="form-control @error('password') is-invalid @enderror">
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Konfirmasi Password</label>
        <input type="password" name="password_confirmation"
          class="form-control">
      </div>

      <button class="btn btn-danger">
        <i class="bi bi-lock"></i> Ganti Password
      </button>

    </form>
  </div>
</div>
@endsection