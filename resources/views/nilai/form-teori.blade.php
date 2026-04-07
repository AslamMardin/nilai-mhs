@extends('layouts.app')
@section('title', 'Input Nilai Teori')
@section('page-title', 'Nilai · Input Teori')
@section('content')
    <div class="card mb-3">
        <div class="card-body py-2 d-flex align-items-center gap-3">
            <div class="flex-grow-1">
                <span class="fw-700">{{ $mataKuliah->nama }}</span>
                <span class="badge bg-light text-dark border ms-1">{{ $mataKuliah->kode }}</span>
                <div class="text-muted small">{{ $mataKuliah->kampus->kode }} · {{ $mataKuliah->kelas->nama }}</div>
            </div>
            <a href="{{ route('nilai.index', $mataKuliah->id) }}" class="btn btn-sm btn-outline-secondary"><i
                    class="bi bi-arrow-left me-1"></i>Kembali</a>
        </div>
    </div>

    <div class="alert alert-info border-0 py-2 mb-3" style="font-size:12px;background:#eff6ff">
        <i class="bi bi-calculator me-1"></i>
        <strong>Rumus:</strong> NA Teori =
        (Keaktifan × {{ $bobot->keaktifan ?? 20 }}%) +
        (Tugas × {{ $bobot->tugas ?? 20 }}%) +
        (UTS × {{ $bobot->uts ?? 25 }}%) +
        (UAS × {{ $bobot->uas ?? 35 }}%)
        &nbsp;|&nbsp; Semua nilai dalam skala 0–100
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-pencil-square text-primary me-1"></i>Form Input Nilai Teori</span>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-fill-sample">Isi Contoh</button>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('nilai.simpan-teori', $mataKuliah->id) }}" id="form-teori">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="tbl-teori">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width:35px">#</th>
                                <th style="width:120px">NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th class="text-center" style="width:100px">
                                    Keaktifan<br><span class="badge bg-secondary" style="font-size:10px">×
                                        {{ $bobot->keaktifan ?? 20 }}%</span>
                                </th>
                                <th class="text-center" style="width:100px">
                                    Tugas<br><span class="badge bg-secondary" style="font-size:10px">×
                                        {{ $bobot->tugas ?? 20 }}%</span>
                                </th>
                                <th class="text-center" style="width:100px">
                                    UTS<br><span class="badge bg-secondary" style="font-size:10px">×
                                        {{ $bobot->uts ?? 25 }}%</span>
                                </th>
                                <th class="text-center" style="width:100px">
                                    UAS<br><span class="badge bg-secondary" style="font-size:10px">×
                                        {{ $bobot->uas ?? 35 }}%</span>
                                </th>
                                <th class="text-center" style="width:100px">
                                    <span class="text-primary fw-700">NA Teori</span><br>
                                    <span style="font-size:10px;color:#64748b">Preview</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mataKuliah->mahasiswa as $idx => $mhs)
                                @php $ex = $nilaiTeoriData[$mhs->id] ?? null; @endphp
                                <input type="hidden" name="nilai[{{ $idx }}][mahasiswa_id]"
                                    value="{{ $mhs->id }}">
                                <tr data-row="{{ $idx }}">
                                    <td class="ps-3 text-muted">{{ $idx + 1 }}</td>
                                    <td><code style="font-size:11px">{{ $mhs->nim }}</code></td>
                                    <td class="fw-500">{{ $mhs->nama }}</td>
                                    @foreach (['keaktifan', 'tugas', 'uts', 'uas'] as $field)
                                        <td class="text-center p-1">
                                            <input type="number" name="nilai[{{ $idx }}][{{ $field }}]"
                                                class="form-control form-control-sm text-center komponen"
                                                data-bobot="{{ [
                                                    'keaktifan' => ($bobot->keaktifan ?? 20) / 100,
                                                    'tugas' => ($bobot->tugas ?? 20) / 100,
                                                    'uts' => ($bobot->uts ?? 25) / 100,
                                                    'uas' => ($bobot->uas ?? 35) / 100,
                                                ][$field] }}"
                                                min="0" max="100" step="0.01"
                                                value="{{ old("nilai.{$idx}.{$field}", $ex?->$field ?? '') }}"
                                                placeholder="0" required>
                                        </td>
                                    @endforeach
                                    <td class="text-center">
                                        <span class="na-preview fw-700" id="prev-{{ $idx }}"
                                            style="font-size:16px">
                                            {{ $ex ? number_format($ex->nilai_akhir_teori, 2) : '—' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('nilai.index', $mataKuliah->id) }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan Nilai
                        Teori</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function hitungNA(row) {
            const inputs = row.querySelectorAll('.komponen');
            let na = 0;
            inputs.forEach(inp => {
                na += (parseFloat(inp.value) || 0) * parseFloat(inp.dataset.bobot);
            });
            na = Math.round(na * 100) / 100;
            const prev = row.querySelector('.na-preview');
            prev.textContent = na.toFixed(2);
            prev.style.color = na >= 75 ? '#16a34a' : na >= 55 ? '#d97706' : '#dc2626';
        }
        document.querySelectorAll('tr[data-row]').forEach(row => {
            row.querySelectorAll('.komponen').forEach(inp => {
                inp.addEventListener('input', () => hitungNA(row));
            });
            if (row.querySelector('.komponen').value) hitungNA(row);
        });
        document.getElementById('btn-fill-sample')?.addEventListener('click', () => {
            document.querySelectorAll('tr[data-row]').forEach(row => {
                row.querySelectorAll('.komponen').forEach(inp => {
                    inp.value = Math.floor(Math.random() * 30 + 70);
                });
                hitungNA(row);
            });
        });
    </script>
@endpush
