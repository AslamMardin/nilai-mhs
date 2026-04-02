@extends('layouts.app')

@section('title', 'Ganti Password')
@section('page-title', 'Ganti Password')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        {{-- Card Profil User --}}
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm border-primary">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-person-circle" style="font-size: 4rem; color:#0d6efd;"></i>
                    </div>
                    <h5 class="card-title">{{ Auth::user()->name }}</h5>
                    <p class="card-text mb-1"><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <p class="card-text mb-1"><strong>Role:</strong> {{ ucfirst(Auth::user()->role) }}</p>
                    @if(Auth::user()->kampus)
                        <p class="card-text mb-0"><strong>Kampus:</strong> {{ Auth::user()->kampus->kode }} - {{ Auth::user()->kampus->nama }}</p>
                    @else
                        <p class="card-text mb-0"><strong>Kampus:</strong> Belum dipilih</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Card Ganti Password --}}
        <div class="col-md-7">
            <div class="card shadow-sm border-success">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-key me-2"></i> Ganti Password
                    </h5>

                   

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Password Lama</label>
                            <input type="password" name="password_lama" class="form-control @error('password_lama') is-invalid @enderror" required>
                            @error('password_lama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password_baru" class="form-control @error('password_baru') is-invalid @enderror" required>
                            @error('password_baru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_baru_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle me-1"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection