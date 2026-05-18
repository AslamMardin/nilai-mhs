@extends('layouts.app')
@section('title', 'Input Nilai Keaktifan Detail')
@section('page-title', 'Nilai · Input Keaktifan')
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

    @php
        $jumlahPertemuan = $mataKuliah->total_pertemuan > 0 ? $mataKuliah->total_pertemuan : 14;
    @endphp

    <div class="alert alert-info border-0 py-2 mb-3" style="font-size:12px;background:#eff6ff">
        <i class="bi bi-info-circle me-1"></i>
        <strong>Info:</strong> Klik pada sel pertemuan untuk memunculkan Checklist Indikator Keaktifan (Maksimal 100 poin). Rata-ratanya akan masuk ke Nilai Teori.
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-ui-checks text-primary me-1"></i>Form Input Nilai Keaktifan Detail</span>
        </div>
        <div class="card-body p-0">
            <form method="POST" action="{{ route('nilai.simpan-keaktifan', $mataKuliah->id) }}" class="p-3">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 text-start align-middle" style="width:35px; min-width:35px;" rowspan="2">#</th>
                                <th class="text-start align-middle" style="min-width:150px;" rowspan="2">Mahasiswa</th>
                                @for($i = 1; $i <= $jumlahPertemuan; $i++)
                                    <th style="min-width:50px;" class="text-center">
                                        <div>P{{ $i }}</div>
                                        <button type="button" class="btn btn-link p-0 text-success btn-fill-col" data-col="{{ $i }}" title="Isi Mengikuti Pembelajaran (65) untuk yang kosong" style="font-size: 14px;">
                                            <i class="bi bi-check-all"></i>
                                        </button>
                                    </th>
                                @endfor
                                <th style="min-width:70px; padding-bottom: 25px" class="align-middle" rowspan="2">Rata-rata</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mataKuliah->mahasiswa as $idx => $mhs)
                                @php 
                                    $ex = isset($keaktifanData[$mhs->id]) ? $keaktifanData[$mhs->id]->keyBy('pertemuan_ke') : collect(); 
                                    $totalSkor = 0;
                                @endphp
                                <input type="hidden" name="nilai[{{ $idx }}][mahasiswa_id]" value="{{ $mhs->id }}">
                                <tr data-row="{{ $idx }}">
                                    <td class="ps-3 text-muted text-start">{{ $idx + 1 }}</td>
                                    <td class="text-start">
                                        <div class="fw-500" style="font-size: 13px;">{{ $mhs->nama }}</div>
                                        <div class="text-muted" style="font-size: 11px;">{{ $mhs->nim }}</div>
                                    </td>
                                    @for($i = 1; $i <= $jumlahPertemuan; $i++)
                                        @php
                                            $skor = $ex->has($i) ? $ex[$i]->skor : '';
                                            $indikator = $ex->has($i) ? $ex[$i]->indikator : [];
                                            $totalSkor += (float) $skor;
                                        @endphp
                                        <td class="p-0 align-middle">
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center cell-keaktifan"
                                                 data-mhs="{{ $mhs->nama }}" data-idx="{{ $idx }}" data-p="{{ $i }}"
                                                 style="cursor: pointer; min-height: 35px;">
                                                
                                                <span class="score-display badge {{ $skor > 0 ? 'bg-success' : 'bg-light text-dark border' }}">
                                                    {{ $skor !== '' ? $skor : '—' }}
                                                </span>
                                                <input type="hidden" name="nilai[{{ $idx }}][pertemuan][{{ $i }}][skor]" class="input-skor" value="{{ $skor }}">
                                                <input type="hidden" name="nilai[{{ $idx }}][pertemuan][{{ $i }}][indikator]" class="input-indikator" value="{{ json_encode($indikator) }}">
                                            </div>
                                        </td>
                                    @endfor
                                    <td class="fw-700 bg-light">
                                        <span class="rata-rata text-primary" style="font-size: 14px;">
                                            {{ number_format($totalSkor / $jumlahPertemuan, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('nilai.index', $mataKuliah->id) }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan Keaktifan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Checklist Keaktifan -->
    <div class="modal fade" id="keaktifanModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-1">
            <h6 class="modal-title fw-bold" id="modalTitle">Detail Keaktifan</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body pt-0">
            <div class="mb-3 text-muted" style="font-size: 13px;" id="modalSubtitle"></div>
            
            <div class="list-group list-group-flush mb-3 border-top border-bottom">
              <label class="list-group-item d-flex gap-2">
                <input class="form-check-input flex-shrink-0 indikator-cb" type="checkbox" value="65" data-key="mengikuti">
                <span>
                  Mengikuti Pembelajaran
                  <small class="d-block text-muted">65 poin</small>
                </span>
              </label>
              <label class="list-group-item d-flex gap-2">
                <input class="form-check-input flex-shrink-0 indikator-cb" type="checkbox" value="15" data-key="menjawab">
                <span>
                  Menjawab
                  <small class="d-block text-muted">15 poin</small>
                </span>
              </label>
              <label class="list-group-item d-flex gap-2">
                <input class="form-check-input flex-shrink-0 indikator-cb" type="checkbox" value="5" data-key="bertanya">
                <span>
                  Bertanya
                  <small class="d-block text-muted">5 poin</small>
                </span>
              </label>
              <label class="list-group-item d-flex gap-2">
                <input class="form-check-input flex-shrink-0 indikator-cb" type="checkbox" value="15" data-key="presentasi">
                <span>
                  Presentasi
                  <small class="d-block text-muted">15 poin</small>
                </span>
              </label>
            </div>

            <div class="d-flex justify-content-between align-items-center px-2">
                <span class="fw-bold">Total Skor</span>
                <div>
                    <span id="modalTotalSkor" class="fw-bold text-primary fs-5">0</span>
                    <span class="text-muted small">/ 100</span>
                </div>
            </div>
            <div class="text-danger small mt-1 text-end d-none" id="modalMaxWarning" style="font-size: 11px;">Maksimal 100 poin</div>
          </div>
          <div class="modal-footer bg-light p-2">
            <button type="button" class="btn btn-sm btn-outline-danger me-auto" id="btn-reset-modal">Kosongkan</button>
            <button type="button" class="btn btn-sm btn-primary px-3" id="btn-save-modal">Terapkan</button>
          </div>
        </div>
      </div>
    </div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('keaktifanModal');
        const modal = new bootstrap.Modal(modalEl);
        
        let currentCell = null;
        const jumlahPertemuan = {{ $jumlahPertemuan }};
        const checkboxes = document.querySelectorAll('.indikator-cb');
        const totalSkorEl = document.getElementById('modalTotalSkor');
        const maxWarning = document.getElementById('modalMaxWarning');

        // Fungsi hitung total di Modal
        const hitungTotalModal = () => {
            let total = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) total += parseInt(cb.value);
            });
            
            if (total > 100) {
                totalSkorEl.textContent = 100;
                totalSkorEl.classList.add('text-danger');
                totalSkorEl.classList.remove('text-primary');
                maxWarning.classList.remove('d-none');
            } else {
                totalSkorEl.textContent = total;
                totalSkorEl.classList.add('text-primary');
                totalSkorEl.classList.remove('text-danger');
                maxWarning.classList.add('d-none');
            }
        };

        checkboxes.forEach(cb => {
            cb.addEventListener('change', hitungTotalModal);
        });

        // Buka modal saat sel di-klik
        document.querySelectorAll('.cell-keaktifan').forEach(cell => {
            cell.addEventListener('click', function() {
                currentCell = this;
                const mhs = this.dataset.mhs;
                const p = this.dataset.p;
                
                document.getElementById('modalTitle').textContent = `Pertemuan ${p}`;
                document.getElementById('modalSubtitle').innerHTML = `<i class="bi bi-person me-1"></i>${mhs}`;

                // Parse existing state
                const indikatorInput = this.querySelector('.input-indikator').value;
                let indikatorState = {};
                try {
                    indikatorState = JSON.parse(indikatorInput) || {};
                } catch(e) {}

                checkboxes.forEach(cb => {
                    const key = cb.dataset.key;
                    // Jika sel kosong total (belum pernah diisi), default tidak dicentang apa-apa.
                    // Atau kita bisa bikin default Hadir dicentang jika tidak ada history, tapi lebih aman kosong saja, 
                    // dosen sendiri yang centang Hadir.
                    cb.checked = indikatorState[key] ? true : false;
                });

                hitungTotalModal();
                modal.show();
            });
        });

        // Tombol Kosongkan (Reset)
        document.getElementById('btn-reset-modal').addEventListener('click', () => {
            checkboxes.forEach(cb => cb.checked = false);
            hitungTotalModal();
        });

        // Tombol Terapkan (Save)
        document.getElementById('btn-save-modal').addEventListener('click', () => {
            if (!currentCell) return;

            let total = 0;
            let state = {};
            let isAnyChecked = false;

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseInt(cb.value);
                    state[cb.dataset.key] = true;
                    isAnyChecked = true;
                }
            });

            if (total > 100) total = 100;

            const skorVal = isAnyChecked ? total : '';
            const jsonState = isAnyChecked ? JSON.stringify(state) : '{}';

            // Update DOM
            currentCell.querySelector('.input-skor').value = skorVal;
            currentCell.querySelector('.input-indikator').value = jsonState;
            
            const badge = currentCell.querySelector('.score-display');
            badge.textContent = skorVal !== '' ? skorVal : '—';
            if (skorVal > 0 || skorVal === 0) {
                badge.className = 'score-display badge bg-success';
            } else {
                badge.className = 'score-display badge bg-light text-dark border';
            }

            // Hitung rata-rata baris
            const row = currentCell.closest('tr');
            const rowInputs = row.querySelectorAll('.input-skor');
            let rowTotal = 0;
            rowInputs.forEach(inp => {
                rowTotal += parseFloat(inp.value) || 0;
            });
            const rata = rowTotal / jumlahPertemuan;
            row.querySelector('.rata-rata').textContent = rata.toFixed(2);

            modal.hide();
        });

        // Tombol Isi Otomatis per Kolom (Ide 3)
        document.querySelectorAll('.btn-fill-col').forEach(btn => {
            btn.addEventListener('click', function() {
                const col = this.dataset.col;
                const cells = document.querySelectorAll(`.cell-keaktifan[data-p="${col}"]`);
                let changed = false;

                cells.forEach(cell => {
                    const inputSkor = cell.querySelector('.input-skor');
                    const inputIndikator = cell.querySelector('.input-indikator');
                    const badge = cell.querySelector('.score-display');

                    // Hanya isi yang masih kosong
                    if (inputSkor.value === '') {
                        inputSkor.value = 65;
                        inputIndikator.value = JSON.stringify({ mengikuti: true });
                        badge.textContent = '65';
                        badge.className = 'score-display badge bg-success';
                        
                        // Hitung ulang rata-rata baris
                        const row = cell.closest('tr');
                        const rowInputs = row.querySelectorAll('.input-skor');
                        let rowTotal = 0;
                        rowInputs.forEach(inp => {
                            rowTotal += parseFloat(inp.value) || 0;
                        });
                        row.querySelector('.rata-rata').textContent = (rowTotal / jumlahPertemuan).toFixed(2);
                        
                        changed = true;
                    }
                });

                if (changed) {
                    // Optional: You could show a tiny toast/alert here if desired
                }
            });
        });
    });
</script>
@endpush
