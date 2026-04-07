@extends('layouts.app')
@section('title','Bobot Nilai')
@section('page-title','Setting Bobot Nilai')

@section('content')

<div class="card">
  <div class="card-header">
    <i class="bi bi-sliders me-1"></i> Setting Bobot Nilai Teori
  </div>

  <div class="card-body">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('bobot.update') }}">
      @csrf

      @php
        $b = $bobot ?? (object)[
          'keaktifan'=>20,
          'tugas'=>20,
          'uts'=>25,
          'uas'=>35
        ];
      @endphp

      <div class="row g-3">

        <div class="col-md-3">
          <label>Keaktifan (%)</label>
          <input type="number" name="keaktifan" class="form-control"
            value="{{ $b->keaktifan }}" required>
        </div>

        <div class="col-md-3">
          <label>Tugas (%)</label>
          <input type="number" name="tugas" class="form-control"
            value="{{ $b->tugas }}" required>
        </div>

        <div class="col-md-3">
          <label>UTS (%)</label>
          <input type="number" name="uts" class="form-control"
            value="{{ $b->uts }}" required>
        </div>

        <div class="col-md-3">
          <label>UAS (%)</label>
          <input type="number" name="uas" class="form-control"
            value="{{ $b->uas }}" required>
        </div>

      </div>

      <div class="mt-3">
        <button class="btn btn-primary">
          <i class="bi bi-save me-1"></i> Simpan
        </button>
      </div>

    </form>
  </div>
</div>

@endsection