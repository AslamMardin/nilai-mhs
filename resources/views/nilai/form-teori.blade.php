@extends('layouts.app')

@section('title', 'Input Nilai Teori — ' . $mataKuliah->nama)
@section('page-title', 'Input Nilai Teori')

@section('content')
<div class="mb-3">
    <h5 class="mb-0">{{ $mataKuliah->nama }}</h5>
    <small class="text-muted">{{ $mataKuliah->kampus->kode }} — {{ $mataKuliah->kelas->nama }}</small>
</div>

<div class="alert alert-info border-0 py-2 mb-3" style="font-size:12px; background:#eff6ff;">
    <i class="bi bi-info-circle me-1"></i>
    <strong>Formula Nilai Teori:</strong>
    NA = (Keaktifan × 20%) + (Tugas × 20%) + (UTS × 25%) + (UAS × 35%)
    &nbsp;|&nbsp; Nilai 0–100
</div>

<div class="card">
    <div class="card-header py-3">
        <i class="bi bi-pencil-square text-primary me-1"></i>
        Form Input Nilai Teori (Semua Mahasiswa)
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('nilai.simpan-teori', $mataKuliah->id) }}" id="formTeori">
            @csrf

            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="tabelNilai">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:40px">#</th>
                            <th style="width:130px">NIM</th>
                            <th>Nama</th>
                            <th class="text-center" style="width:110px">
                                Keaktifan
                                <span class="badge bg-secondary" style="font-size:10px">20%</span>
                            </th>
                            <th class="text-center" style="width:110px">
                                Tugas
                                <span class="badge bg-secondary" style="font-size:10px">20%</span>
                            </th>
                            <th class="text-center" style="width:110px">
                                UTS
                                <span class="badge bg-secondary" style="font-size:10px">25%</span>
                            </th>
                            <th class="text-center" style="width:110px">
                                UAS
                                <span class="badge bg-secondary" style="font-size:10px">35%</span>
                            </th>
                            <th class="text-center" style="width:110px">
                                <span class="text-primary fw-600">NA Teori</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mataKuliah->mahasiswa as $idx => $mhs)
                        @php $existing = $nilaiTeoriData[$mhs->id] ?? null; @endphp
                        <input type="hidden" name="nilai[{{ $idx }}][mahasiswa_id]" value="{{ $mhs->id }}">
                        <tr data-row="{{ $idx }}">
                            <td class="ps-3 text-muted">{{ $idx + 1 }}</td>
                            <td><code class="small">{{ $mhs->nim }}</code></td>
                            <td>{{ $mhs->nama }}</td>
                            <td class="text-center">
                                <input type="number"
                                       name="nilai[{{ $idx }}][keaktifan]"
                                       class="form-control form-control-sm text-center komponen"
                                       min="0" max="100" step="0.01"
                                       value="{{ old("nilai.{$idx}.keaktifan", $existing?->keaktifan ?? '') }}"
                                       placeholder="0-100" required>
                            </td>
                            <td class="text-center">
                                <input type="number"
                                       name="nilai[{{ $idx }}][tugas]"
                                       class="form-control form-control-sm text-center komponen"
                                       min="0" max="100" step="0.01"
                                       value="{{ old("nilai.{$idx}.tugas", $existing?->tugas ?? '') }}"
                                       placeholder="0-100" required>
                            </td>
                            <td class="text-center">
                                <input type="number"
                                       name="nilai[{{ $idx }}][uts]"
                                       class="form-control form-control-sm text-center komponen"
                                       min="0" max="100" step="0.01"
                                       value="{{ old("nilai.{$idx}.uts", $existing?->uts ?? '') }}"
                                       placeholder="0-100" required>
                            </td>
                            <td class="text-center">
                                <input type="number"
                                       name="nilai[{{ $idx }}][uas]"
                                       class="form-control form-control-sm text-center komponen"
                                       min="0" max="100" step="0.01"
                                       value="{{ old("nilai.{$idx}.uas", $existing?->uas ?? '') }}"
                                       placeholder="0-100" required>
                            </td>
                            <td class="text-center">
                                <span class="na-preview fw-700 text-primary" id="na_{{ $idx }}">
                                    {{ $existing?->nilai_akhir_teori ?? '—' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('nilai.index', $mataKuliah->id) }}"
                   class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan Nilai Teori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
/**
 * Preview nilai akhir teori secara real-time
 * NA = (keaktifan*0.20) + (tugas*0.20) + (uts*0.25) + (uas*0.35)
 */
document.querySelectorAll('tr[data-row]').forEach(row => {
    const idx     = row.dataset.row;
    const inputs  = row.querySelectorAll('.komponen');
    const preview = document.getElementById('na_' + idx);

    inputs.forEach(input => {
        input.addEventListener('input', function () {
            const vals = [...inputs].map(i => parseFloat(i.value) || 0);
            const na = (vals[0] * 0.20) + (vals[1] * 0.20) + (vals[2] * 0.25) + (vals[3] * 0.35);
            preview.textContent = na.toFixed(2);

            // Warnakan berdasarkan nilai
            if (na >= 75)      preview.className = 'na-preview fw-700 text-success';
            else if (na >= 55) preview.className = 'na-preview fw-700 text-warning';
            else               preview.className = 'na-preview fw-700 text-danger';
        });
    });

    // Trigger pada load jika sudah ada nilai
    if (inputs[0].value) inputs[0].dispatchEvent(new Event('input'));
});
</script>
@endpush
