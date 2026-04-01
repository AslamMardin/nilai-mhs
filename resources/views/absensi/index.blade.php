@extends('layouts.app')

@section('title', 'Input Absensi — ' . $mataKuliah->nama)
@section('page-title', 'Absensi')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h5 class="mb-0">{{ $mataKuliah->nama }}</h5>
        <small class="text-muted">
            {{ $mataKuliah->kampus->kode }} — {{ $mataKuliah->kelas->nama }} |
            {{ $mataKuliah->total_pertemuan }} pertemuan | Dosen: {{ $mataKuliah->dosen ?? '-' }}
        </small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('absensi.rekap', $mataKuliah->id) }}"
           class="btn btn-outline-info btn-sm">
            <i class="bi bi-table me-1"></i> Rekap Kehadiran
        </a>
    </div>
</div>

{{-- ── Rekap Kehadiran Ringkas ─────────────────────────────── --}}
<div class="row g-2 mb-3">
    @foreach($rekapKehadiran as $mhsId => $rek)
    @php $mhs = $mataKuliah->mahasiswa->find($mhsId); @endphp
    <div class="col-md-4 col-lg-3">
        <div class="card py-2 px-3 {{ $rek['lolos'] ? 'border-success' : 'border-danger' }}">
            <div class="d-flex justify-content-between align-items-center">
                <span class="small fw-500">{{ $mhs->nama }}</span>
                <span class="badge {{ $rek['lolos'] ? 'bg-success' : 'bg-danger' }}">
                    {{ $rek['persentase'] }}%
                </span>
            </div>
            <div class="text-muted" style="font-size:11px">Poin: {{ $rek['poin'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Form Input Absensi Per Pertemuan ──────────────────────── --}}
<div class="card">
    <div class="card-header py-3 d-flex align-items-center gap-3">
        <i class="bi bi-calendar-event text-primary"></i>
        <span>Input Absensi</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('absensi.simpan', $mataKuliah->id) }}">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label fw-500">Pertemuan Ke-</label>
                    <select name="pertemuan_ke" class="form-select" required>
                        @for($i = 1; $i <= $mataKuliah->total_pertemuan; $i++)
                        <option value="{{ $i }}" {{ old('pertemuan_ke', 1) == $i ? 'selected' : '' }}>
                            Pertemuan {{ $i }}
                        </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-500">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control"
                           value="{{ old('tanggal', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="alert alert-light border mb-0 py-2 w-100" style="font-size:12px;">
                        <strong>Keterangan Bobot:</strong>
                        <span class="status-pill pill-H ms-1">H</span> Hadir=2 &nbsp;
                        <span class="status-pill pill-T">T</span> Terlambat=1 &nbsp;
                        <span class="status-pill pill-S">S</span> Sakit=1 &nbsp;
                        <span class="status-pill pill-I">I</span> Izin=0 &nbsp;
                        <span class="status-pill pill-A">A</span> Absen=0
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:50px">#</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th class="text-center" style="width:240px">Status Kehadiran</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mataKuliah->mahasiswa as $idx => $mhs)
                        <input type="hidden" name="absensi[{{ $idx }}][mahasiswa_id]" value="{{ $mhs->id }}">
                        <tr>
                            <td class="ps-3 text-muted">{{ $idx + 1 }}</td>
                            <td><code>{{ $mhs->nim }}</code></td>
                            <td>{{ $mhs->nama }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    @foreach(['H' => 'success', 'T' => 'warning', 'S' => 'info', 'I' => 'secondary', 'A' => 'danger'] as $status => $warna)
                                    <input type="radio"
                                           class="btn-check"
                                           name="absensi[{{ $idx }}][status]"
                                           id="status_{{ $mhs->id }}_{{ $status }}"
                                           value="{{ $status }}"
                                           {{ old("absensi.{$idx}.status", 'H') === $status ? 'checked' : '' }}>
                                    <label class="btn btn-outline-{{ $warna }}"
                                           for="status_{{ $mhs->id }}_{{ $status }}">
                                        {{ $status }}
                                    </label>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <input type="text"
                                       name="absensi[{{ $idx }}][keterangan]"
                                       class="form-control form-control-sm"
                                       placeholder="Opsional..."
                                       value="{{ old("absensi.{$idx}.keterangan") }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="setAllHadir">
                    <i class="bi bi-check-all me-1"></i> Set Semua Hadir
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan Absensi
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Grid Rekap 16 Pertemuan ─────────────────────────────── --}}
<div class="card mt-3">
    <div class="card-header py-3">
        <i class="bi bi-grid-3x3 text-secondary me-1"></i> Grid Absensi (Semua Pertemuan)
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th class="text-start ps-3" style="min-width:160px">Mahasiswa</th>
                        @for($p = 1; $p <= $mataKuliah->total_pertemuan; $p++)
                        <th style="width:40px">{{ $p }}</th>
                        @endfor
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mataKuliah->mahasiswa as $mhs)
                    <tr>
                        <td class="text-start ps-3 small">{{ $mhs->nama }}</td>
                        @for($p = 1; $p <= $mataKuliah->total_pertemuan; $p++)
                        @php
                            $key = "{$mhs->id}_{$p}";
                            $absen = $existingAbsensi[$key]->first() ?? null;
                        @endphp
                        <td>
                            @if($absen)
                            <span class="status-pill pill-{{ $absen->status }}">{{ $absen->status }}</span>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                        @endfor
                        <td>
                            @php $rek = $rekapKehadiran[$mhs->id] ?? null; @endphp
                            @if($rek)
                            <span class="badge {{ $rek['lolos'] ? 'bg-success' : 'bg-danger' }}">
                                {{ $rek['persentase'] }}%
                            </span>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Tombol set semua mahasiswa menjadi Hadir
    document.getElementById('setAllHadir').addEventListener('click', function () {
        document.querySelectorAll('input[type=radio][value=H]').forEach(r => r.checked = true);
    });
</script>
@endpush
