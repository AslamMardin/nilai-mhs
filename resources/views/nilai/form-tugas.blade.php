@extends('layouts.app')
@section('title', 'Input Nilai Tugas Detail')
@section('page-title', 'Nilai · Input Tugas')
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
        <i class="bi bi-info-circle me-1"></i>
        <strong>Info:</strong> Rata-rata dari semua nilai tugas akan otomatis disimpan ke kolom <strong>Tugas</strong> pada Nilai Teori. Tambahkan kolom tugas baru dengan tombol "Tambah Kolom Tugas".
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-journal-text text-primary me-1"></i>Form Input Nilai Tugas Detail</span>
            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-tambah-tugas">
                <i class="bi bi-plus-circle me-1"></i>Tambah Kolom Tugas
            </button>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('nilai.simpan-tugas', $mataKuliah->id) }}" id="form-tugas">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center table-hover table-sm" id="tbl-tugas">
                        <thead class="table-light">
                            <tr id="tr-header">
                                <th class="ps-3 text-start" style="width:35px; min-width:35px;">#</th>
                                <th class="text-start" style="min-width:150px;">Mahasiswa</th>
                                @foreach($semuaTugas as $namaTugas)
                                    <th style="min-width:100px;" class="col-tugas" data-nama="{{ $namaTugas }}">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="nama-tugas-label">{{ $namaTugas }}</span>
                                            <button type="button" class="btn btn-sm btn-link text-danger p-0 hapus-kolom" title="Hapus Kolom"><i class="bi bi-x-circle-fill"></i></button>
                                        </div>
                                    </th>
                                @endforeach
                                <th style="min-width:80px;">Rata-rata</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mataKuliah->mahasiswa as $idx => $mhs)
                                @php 
                                    $ex = isset($tugasData[$mhs->id]) ? $tugasData[$mhs->id]->keyBy('nama_tugas') : collect(); 
                                    $totalSkor = 0;
                                    $jumlahTugas = count($semuaTugas);
                                @endphp
                                <input type="hidden" name="nilai[{{ $idx }}][mahasiswa_id]" value="{{ $mhs->id }}">
                                <tr data-row="{{ $idx }}">
                                    <td class="ps-3 text-muted text-start">{{ $idx + 1 }}</td>
                                    <td class="text-start">
                                        <div class="fw-500" style="font-size: 13px;">{{ $mhs->nama }}</div>
                                        <div class="text-muted" style="font-size: 11px;">{{ $mhs->nim }}</div>
                                    </td>
                                    @foreach($semuaTugas as $namaTugas)
                                        @php
                                            $skor = $ex->has($namaTugas) ? $ex[$namaTugas]->skor : '';
                                            $totalSkor += (float) $skor;
                                        @endphp
                                        <td class="p-1 col-tugas-input">
                                            <input type="number" name="nilai[{{ $idx }}][tugas][{{ $namaTugas }}]"
                                                class="form-control form-control-sm text-center input-skor"
                                                min="0" max="100" step="0.01"
                                                value="{{ $skor }}" placeholder="—" style="font-size: 12px; padding: 2px 4px;">
                                        </td>
                                    @endforeach
                                    <td class="fw-700">
                                        <span class="rata-rata text-primary" style="font-size: 14px;">
                                            {{ $jumlahTugas > 0 ? number_format($totalSkor / $jumlahTugas, 2) : '0.00' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('nilai.index', $mataKuliah->id) }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan Tugas</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const attachListeners = () => {
            document.querySelectorAll('tr[data-row]').forEach(row => {
                const inputs = row.querySelectorAll('.input-skor');
                const rataRataEl = row.querySelector('.rata-rata');
                
                const hitungRataRata = () => {
                    let total = 0;
                    let count = 0;
                    inputs.forEach(inp => {
                        total += parseFloat(inp.value) || 0;
                        count++;
                    });
                    const rata = count > 0 ? total / count : 0;
                    rataRataEl.textContent = rata.toFixed(2);
                };

                inputs.forEach(inp => {
                    inp.removeEventListener('input', hitungRataRata); // prevent duplicate listener
                    inp.addEventListener('input', hitungRataRata);
                });
                hitungRataRata();
            });

            document.querySelectorAll('.hapus-kolom').forEach(btn => {
                btn.onclick = function() {
                    const th = this.closest('th');
                    const index = Array.from(th.parentNode.children).indexOf(th);
                    
                    if (confirm(`Yakin ingin menghapus kolom ${th.dataset.nama}?`)) {
                        document.getElementById('tbl-tugas').querySelectorAll('tr').forEach(tr => {
                            if (tr.children[index]) {
                                tr.children[index].remove();
                            }
                        });
                        attachListeners();
                    }
                }
            });
        };

        attachListeners();

        document.getElementById('btn-tambah-tugas').addEventListener('click', () => {
            const namaTugasBaru = prompt("Masukkan nama tugas baru (misal: Makalah, Presentasi):");
            if (!namaTugasBaru) return;

            // Check if exist
            const trHeader = document.getElementById('tr-header');
            const thList = trHeader.querySelectorAll('.col-tugas');
            let isExist = false;
            thList.forEach(th => {
                if (th.dataset.nama === namaTugasBaru) isExist = true;
            });
            
            if (isExist) {
                alert("Nama tugas sudah ada!");
                return;
            }

            // Tambah TH (sebelum kolom Rata-rata)
            const th = document.createElement('th');
            th.className = 'col-tugas';
            th.style.minWidth = '100px';
            th.dataset.nama = namaTugasBaru;
            th.innerHTML = `
                <div class="d-flex align-items-center justify-content-between">
                    <span class="nama-tugas-label">${namaTugasBaru}</span>
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 hapus-kolom" title="Hapus Kolom"><i class="bi bi-x-circle-fill"></i></button>
                </div>
            `;
            const thRataRata = trHeader.lastElementChild;
            trHeader.insertBefore(th, thRataRata);

            // Tambah TD di tbody
            document.querySelectorAll('tr[data-row]').forEach(tr => {
                const td = document.createElement('td');
                td.className = 'p-1 col-tugas-input';
                const idx = tr.dataset.row;
                td.innerHTML = `
                    <input type="number" name="nilai[${idx}][tugas][${namaTugasBaru}]"
                        class="form-control form-control-sm text-center input-skor"
                        min="0" max="100" step="0.01" value="" placeholder="—" style="font-size: 12px; padding: 2px 4px;">
                `;
                const tdRataRata = tr.lastElementChild;
                tr.insertBefore(td, tdRataRata);
            });

            attachListeners();
        });
    });
</script>
@endpush
