@extends('layouts.app')
@section('title','Input Nilai Praktikum')
@section('page-title','Nilai · Input Praktikum')
@section('content')
<div class="card mb-3">
  <div class="card-body py-2 d-flex align-items-center gap-3">
    <div class="flex-grow-1">
      <span class="fw-700">{{ $mataKuliah->nama }}</span>
      <span class="badge bg-light text-dark border ms-1">{{ $mataKuliah->kode }}</span>
      <div class="text-muted small">{{ $mataKuliah->kampus->kode }} · {{ $mataKuliah->kelas->nama }}</div>
    </div>
    <a href="{{ route('nilai.index',$mataKuliah->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
  </div>
</div>

<div class="alert alert-success border-0 py-2 mb-3" style="font-size:12px;background:#f0fdf4">
  <i class="bi bi-tools me-1"></i>
  <strong>Bobot Praktikum: 100%</strong> — Nilai langsung menjadi Nilai Akhir Praktikum. Skala 0–100.
</div>

<div class="card">
  <div class="card-header"><i class="bi bi-tools text-success me-1"></i>Form Input Nilai Praktikum</div>
  <div class="card-body">
    <form method="POST" action="{{ route('nilai.simpan-praktikum',$mataKuliah->id) }}">
      @csrf
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th class="ps-3" style="width:35px">#</th>
              <th style="width:120px">NIM</th>
              <th>Nama Mahasiswa</th>
              <th class="text-center" style="width:160px">
                Nilai Praktikum<br><span class="badge bg-success" style="font-size:10px">100%</span>
              </th>
              <th class="text-center" style="width:100px">Keterangan</th>
            </tr>
          </thead>
          <tbody>
            @foreach($mataKuliah->mahasiswa as $idx => $mhs)
            @php $ex = $nilaiPrakData[$mhs->id] ?? null; @endphp
            <input type="hidden" name="nilai[{{ $idx }}][mahasiswa_id]" value="{{ $mhs->id }}">
            <tr>
              <td class="ps-3 text-muted">{{ $idx+1 }}</td>
              <td><code style="font-size:11px">{{ $mhs->nim }}</code></td>
              <td class="fw-500">{{ $mhs->nama }}</td>
              <td class="text-center p-1">
                <input type="number" name="nilai[{{ $idx }}][nilai_praktikum]"
                       class="form-control text-center fw-600"
                       style="font-size:16px"
                       min="0" max="100" step="0.01"
                       value="{{ old("nilai.{$idx}.nilai_praktikum", $ex?->nilai_praktikum ?? '') }}"
                       placeholder="0–100" required>
              </td>
              <td class="text-center">
                @if($ex)
                  <span class="badge {{ $ex->nilai_praktikum>=75?'bg-success':($ex->nilai_praktikum>=55?'bg-warning text-dark':'bg-danger') }}">
                    {{ $ex->nilai_praktikum >= 75 ? 'Baik' : ($ex->nilai_praktikum >= 55 ? 'Cukup' : 'Kurang') }}
                  </span>
                @else <span class="text-muted small">—</span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between mt-3">
        <a href="{{ route('nilai.index',$mataKuliah->id) }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-success px-4"><i class="bi bi-save me-1"></i>Simpan Nilai Praktikum</button>
      </div>
    </form>
  </div>
</div>
@endsection
