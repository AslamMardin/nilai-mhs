@extends('layouts.app')
@section('title', 'Informasi Sistem')
@section('page-title', 'Informasi Sistem')

@push('styles')
<style>
    /* ===== Hero Banner ===== */
    .info-hero {
        background: linear-gradient(135deg, #1a4a7a 0%, #0f2d4a 60%, #162a3e 100%);
        border-radius: 16px;
        padding: 40px 36px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 28px;
    }
    .info-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 280px; height: 280px;
        background: rgba(232,160,32,.12);
        border-radius: 50%;
    }
    .info-hero::after {
        content: '';
        position: absolute;
        bottom: -80px; left: 40%;
        width: 200px; height: 200px;
        background: rgba(255,255,255,.05);
        border-radius: 50%;
    }
    .info-hero h1 { font-size: 26px; font-weight: 800; margin-bottom: 8px; }
    .info-hero p  { font-size: 14px; opacity: .8; margin: 0; max-width: 600px; }
    .hero-tag {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(232,160,32,.2); border: 1px solid rgba(232,160,32,.4);
        color: #fbbf24; border-radius: 20px; padding: 4px 12px;
        font-size: 11px; font-weight: 700; letter-spacing: .05em;
        text-transform: uppercase; margin-bottom: 14px;
    }
    /* ===== Section Titles ===== */
    .sec-title {
        display: flex; align-items: center; gap: 10px;
        font-size: 17px; font-weight: 700; color: #1e293b;
        margin-bottom: 18px; padding-bottom: 12px;
        border-bottom: 2px solid #e2e8f0;
    }
    .sec-title .icon-box {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
    /* ===== Rumus Cards ===== */
    .formula-card {
        background: #f8fafc; border: 1.5px solid #e2e8f0;
        border-radius: 12px; padding: 20px 24px;
        margin-bottom: 16px; transition: border-color .2s;
    }
    .formula-card:hover { border-color: #93c5fd; }
    .formula-card .formula-label {
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .06em; color: #64748b; margin-bottom: 8px;
    }
    .formula-box {
        background: #1e293b; border-radius: 8px;
        padding: 12px 18px; font-family: 'Courier New', monospace;
        font-size: 13.5px; color: #7dd3fc; font-weight: 600;
        letter-spacing: .02em; margin: 10px 0;
    }
    /* ===== Bobot Table ===== */
    .bobot-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 16px; border-radius: 10px;
        margin-bottom: 8px; background: #f8fafc;
        border: 1px solid #e2e8f0; transition: background .15s;
    }
    .bobot-row:hover { background: #eff6ff; border-color: #bfdbfe; }
    .bobot-pct {
        font-size: 24px; font-weight: 800; color: #1a4a7a; min-width: 56px; text-align: right;
    }
    .bobot-bar-wrap {
        flex: 1; margin: 0 16px; height: 8px;
        background: #e2e8f0; border-radius: 4px; overflow: hidden;
    }
    .bobot-bar { height: 100%; border-radius: 4px; }
    /* ===== Step Flow ===== */
    .step-flow {
        display: flex; gap: 0; align-items: stretch;
        margin-bottom: 12px; flex-wrap: wrap;
    }
    .step-item {
        flex: 1; min-width: 120px;
        background: #f8fafc; border: 1.5px solid #e2e8f0;
        border-radius: 10px; padding: 14px 12px; text-align: center;
        position: relative;
    }
    .step-item + .step-item::before {
        content: '→';
        position: absolute; left: -16px; top: 50%; transform: translateY(-50%);
        font-size: 18px; color: #94a3b8;
    }
    .step-item .step-num {
        width: 24px; height: 24px; border-radius: 50%;
        background: #1a4a7a; color: #fff; font-size: 11px; font-weight: 700;
        display: flex; align-items: center; justify-content: center; margin: 0 auto 8px;
    }
    .step-item .step-val { font-size: 13px; font-weight: 700; color: #1e293b; }
    .step-item .step-sub { font-size: 10px; color: #64748b; margin-top: 2px; }
    /* ===== Mutu Table ===== */
    .mutu-row {
        display: grid; grid-template-columns: 64px 1fr 100px 1fr;
        align-items: center; gap: 12px; padding: 10px 16px;
        border-radius: 8px; margin-bottom: 6px;
    }
    .mutu-row:hover { background: #f8fafc; }
    /* ===== Info Highlight ===== */
    .hl-box {
        background: linear-gradient(135deg,#eff6ff,#f0fdf4);
        border: 1.5px solid #bfdbfe; border-radius: 12px; padding: 18px 20px;
        margin-bottom: 16px;
    }
    .hl-box.warn { background: linear-gradient(135deg,#fffbeb,#fff7ed); border-color: #fde68a; }
    .hl-box.green { background: linear-gradient(135deg,#f0fdf4,#ecfdf5); border-color: #6ee7b7; }
    /* ===== Feature Grid ===== */
    .feat-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); gap: 14px; }
    .feat-card {
        background: #fff; border: 1.5px solid #e2e8f0;
        border-radius: 12px; padding: 18px 16px;
        transition: transform .2s, box-shadow .2s, border-color .2s;
    }
    .feat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,.06);
        border-color: #93c5fd;
    }
    .feat-card .feat-icon {
        width: 42px; height: 42px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; margin-bottom: 12px;
    }
    .feat-card h6 { font-size: 13.5px; font-weight: 700; margin-bottom: 6px; color: #1e293b; }
    .feat-card p  { font-size: 12px; color: #64748b; margin: 0; line-height: 1.6; }
    /* ===== Timeline Workflow ===== */
    .tl { position: relative; padding-left: 36px; }
    .tl::before {
        content: ''; position: absolute; left: 14px; top: 6px;
        bottom: 6px; width: 2px; background: #e2e8f0; border-radius: 1px;
    }
    .tl-item { position: relative; margin-bottom: 22px; }
    .tl-dot {
        position: absolute; left: -36px; top: 2px;
        width: 28px; height: 28px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 700; color: #fff;
        z-index: 1;
    }
    .tl-item h6 { font-size: 13.5px; font-weight: 700; margin-bottom: 4px; color: #1e293b; }
    .tl-item p  { font-size: 12px; color: #64748b; margin: 0; line-height: 1.6; }
    /* ===== Tech stack ===== */
    .tech-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: #f1f5f9; border: 1px solid #e2e8f0;
        border-radius: 20px; padding: 5px 14px;
        font-size: 12px; font-weight: 600; color: #475569;
        margin: 4px;
    }
    .tech-badge i { color: #1a4a7a; }
</style>
@endpush

@section('content')
<div class="cw">

    {{-- HERO --}}
    <div class="info-hero">
        <div class="hero-tag"><i class="bi bi-info-circle-fill"></i> Dokumentasi Sistem</div>
        <h1><i class="bi bi-mortarboard-fill me-2"></i>Sistem Penilaian Akademik</h1>
        <p>Panduan lengkap aturan penilaian, alur kerja, fitur, dan formula perhitungan nilai mahasiswa yang digunakan pada aplikasi ini.</p>
    </div>

    <div class="row g-4">

        {{-- ═══════════════════════════════════════════
             KOLOM KIRI (8 kolom)
        ═══════════════════════════════════════════ --}}
        <div class="col-lg-8">

            {{-- 1. ATURAN PENILAIAN --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <div class="sec-title">
                        <div class="icon-box" style="background:#eff6ff;color:#1a4a7a"><i class="bi bi-percent"></i></div>
                        Aturan Penilaian & Formula
                    </div>

                    {{-- Bobot Komponen --}}
                    <p class="text-muted mb-3" style="font-size:13px">Nilai mahasiswa dihitung dari <strong>4 komponen bobot 100%</strong> sebagai <em>NA Murni</em>, kemudian ditambah bonus keaktifan untuk menghasilkan <em>Nilai Finish</em>.</p>

                    @php
                        $bobotData = [
                            ['label'=>'Kehadiran','pct'=>$bobot->kehadiran??25,'bar'=>'#1a4a7a','icon'=>'bi-calendar-check','desc'=>'Persentase kehadiran riil dari total pertemuan'],
                            ['label'=>'Tugas','pct'=>$bobot->tugas??20,'bar'=>'#0891b2','icon'=>'bi-journal-text','desc'=>'Rata-rata nilai tugas terstruktur yang diinput dosen'],
                            ['label'=>'UTS','pct'=>$bobot->uts??25,'bar'=>'#7c3aed','icon'=>'bi-pencil-square','desc'=>'Nilai Ujian Tengah Semester'],
                            ['label'=>'UAS','pct'=>$bobot->uas??30,'bar'=>'#dc2626','icon'=>'bi-file-earmark-check','desc'=>'Nilai Ujian Akhir Semester'],
                        ];
                        $total = array_sum(array_column($bobotData,'pct'));
                    @endphp

                    @foreach($bobotData as $b)
                    <div class="bobot-row">
                        <div style="min-width:130px">
                            <i class="bi {{ $b['icon'] }} me-2" style="color:{{ $b['bar'] }}"></i>
                            <strong style="font-size:13px">{{ $b['label'] }}</strong>
                            <div style="font-size:11px;color:#94a3b8;margin-top:2px;padding-left:22px">{{ $b['desc'] }}</div>
                        </div>
                        <div class="bobot-bar-wrap">
                            <div class="bobot-bar" style="width:{{ $b['pct'] }}%;background:{{ $b['bar'] }}"></div>
                        </div>
                        <div class="bobot-pct">{{ $b['pct'] }}%</div>
                    </div>
                    @endforeach

                    <div class="d-flex justify-content-end mb-4">
                        <span class="badge" style="background:#1e293b;font-size:12px;padding:6px 14px">
                            Total Bobot = {{ $total }}%
                        </span>
                    </div>

                    {{-- Formula NA Murni --}}
                    <div class="formula-card">
                        <div class="formula-label"><i class="bi bi-calculator me-1"></i>Step 1 — NA Murni (100%)</div>
                        <div class="formula-box">
                            NA Murni = (Hadir × {{ $bobot->kehadiran??25 }}%) + (Tugas × {{ $bobot->tugas??20 }}%) + (UTS × {{ $bobot->uts??25 }}%) + (UAS × {{ $bobot->uas??30 }}%)
                        </div>
                        <small class="text-muted">Nilai Tugas yang tampil di tabel adalah nilai murni yang <strong>sama persis</strong> dengan nilai yang dipakai dalam rumus ini — tanpa angka tersembunyi, tanpa nilai praktikum.</small>
                    </div>

                    {{-- Formula Bonus --}}
                    <div class="formula-card">
                        <div class="formula-label"><i class="bi bi-star me-1 text-warning"></i>Step 2 — Bonus Keaktifan (Maks. 10%)</div>
                        <div class="formula-box">
                            Bonus Keaktifan = Skor Keaktifan × 10%
                        </div>
                        <div class="row g-2 mt-1">
                            <div class="col-auto">
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3" style="font-size:12px">
                                    Contoh: Keaktifan 58,13 → Bonus = +5,81
                                </span>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3" style="font-size:12px">
                                    Contoh: Keaktifan 64,06 → Bonus = +6,41
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Formula Nilai Finish --}}
                    <div class="formula-card" style="border-color:#bfdbfe;background:#eff6ff">
                        <div class="formula-label" style="color:#1a4a7a"><i class="bi bi-flag-fill me-1 text-primary"></i>Step 3 — Nilai Finish (Hasil Akhir Kalkulasi)</div>
                        <div class="formula-box" style="background:#0f2d4a">
                            Nilai Finish = MIN( 100 , NA Murni + Bonus Keaktifan )
                        </div>
                        <small class="text-muted">Jika hasil melebihi 100, secara otomatis dibatasi menjadi 100.</small>
                    </div>

                    {{-- Formula NA = Nilai Finish --}}
                    <div class="hl-box green">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            <strong style="font-size:13.5px">Nilai Akhir (NA) & Huruf Mutu</strong>
                        </div>
                        <p class="mb-0" style="font-size:13px;color:#374151">
                            <strong>NA = Nilai Finish</strong> — Nilai Akhir dan Huruf Mutu ditetapkan langsung dari Nilai Finish
                            tanpa nilai alternatif, tanpa batas minimum kehadiran tersembunyi.
                        </p>
                    </div>

                    {{-- Alur Kalkulasi --}}
                    <div class="mt-3 mb-1" style="font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em">Alur Kalkulasi</div>
                    <div class="step-flow gap-2">
                        <div class="step-item">
                            <div class="step-num">1</div>
                            <div class="step-val">Input Nilai</div>
                            <div class="step-sub">Hadir, Tugas, UTS, UAS, Keaktifan</div>
                        </div>
                        <div class="step-item">
                            <div class="step-num">2</div>
                            <div class="step-val">NA Murni</div>
                            <div class="step-sub">Bobot 100%</div>
                        </div>
                        <div class="step-item">
                            <div class="step-num">3</div>
                            <div class="step-val">+ Bonus</div>
                            <div class="step-sub">Keaktifan × 10%</div>
                        </div>
                        <div class="step-item" style="background:#eff6ff;border-color:#93c5fd">
                            <div class="step-num" style="background:#1d4ed8">4</div>
                            <div class="step-val" style="color:#1d4ed8">Nilai Finish</div>
                            <div class="step-sub">MIN(100, ...)</div>
                        </div>
                        <div class="step-item" style="background:#f0fdf4;border-color:#6ee7b7">
                            <div class="step-num" style="background:#16a34a">5</div>
                            <div class="step-val" style="color:#16a34a">NA & Mutu</div>
                            <div class="step-sub">A, B, C, D, E</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. HURUF MUTU & KELULUSAN --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <div class="sec-title">
                        <div class="icon-box" style="background:#fef3c7;color:#d97706"><i class="bi bi-award-fill"></i></div>
                        Huruf Mutu & Syarat Kelulusan
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div style="font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px">Konversi Huruf Mutu</div>
                            @php
                                $mutu = [
                                    ['huruf'=>'A','min'=>85,'max'=>100,'bg'=>'#16a34a','light'=>'#dcfce7'],
                                    ['huruf'=>'B','min'=>75,'max'=>84.99,'bg'=>'#2563eb','light'=>'#dbeafe'],
                                    ['huruf'=>'C','min'=>65,'max'=>74.99,'bg'=>'#d97706','light'=>'#fef3c7'],
                                    ['huruf'=>'D','min'=>55,'max'=>64.99,'bg'=>'#ea580c','light'=>'#ffedd5'],
                                    ['huruf'=>'E','min'=>0,'max'=>54.99,'bg'=>'#dc2626','light'=>'#fee2e2'],
                                ];
                            @endphp
                            @foreach($mutu as $m)
                            <div class="mutu-row">
                                <span class="badge fw-bold" style="background:{{ $m['bg'] }};font-size:15px;padding:6px 10px">{{ $m['huruf'] }}</span>
                                <div>
                                    <div style="font-size:12px;font-weight:600;color:#1e293b">
                                        @if($m['min'] == 0) &lt; 55 @else ≥ {{ $m['min'] }} @endif
                                    </div>
                                    <div style="font-size:10px;color:#94a3b8">
                                        @if($m['min'] == 0) 0 — 54,99 @else {{ $m['min'] }} — {{ $m['max'] }} @endif
                                    </div>
                                </div>
                                <div style="flex:1;height:6px;background:#e2e8f0;border-radius:3px;overflow:hidden;margin:0 8px">
                                    <div style="height:100%;background:{{ $m['bg'] }};width:{{ $m['min'] == 0 ? 55 : (100 - $m['min'] + 5) }}%;border-radius:3px"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="col-md-6">
                            <div style="font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px">Syarat Kelulusan</div>
                            <div class="hl-box green mb-3">
                                <div class="fw-bold mb-2" style="font-size:13px;color:#15803d">
                                    <i class="bi bi-check-circle-fill me-1"></i>Kriteria LULUS
                                </div>
                                <div style="font-size:13px">
                                    <span class="badge bg-success me-1">✓</span> Kehadiran <strong>≥ 60%</strong><br>
                                    <div class="text-center my-1" style="font-size:12px;color:#64748b">DAN</div>
                                    <span class="badge bg-success me-1">✓</span> Nilai Akhir <strong>≥ 55</strong>
                                </div>
                            </div>
                            <div class="hl-box warn">
                                <div class="fw-bold mb-1" style="font-size:13px;color:#92400e">
                                    <i class="bi bi-x-circle-fill me-1"></i>Tidak Lulus jika:
                                </div>
                                <ul class="mb-0 ps-3" style="font-size:12px;color:#78350f">
                                    <li>Kehadiran &lt; 60%, <strong>atau</strong></li>
                                    <li>Nilai Akhir &lt; 55, <strong>atau</strong></li>
                                    <li>Keduanya tidak terpenuhi</li>
                                </ul>
                                <div class="mt-2" style="font-size:11px;color:#92400e">
                                    Sistem otomatis mencatat alasan ketidaklulusan untuk setiap mahasiswa.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. STRUKTUR KOLOM TABEL NILAI --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <div class="sec-title">
                        <div class="icon-box" style="background:#f0fdf4;color:#16a34a"><i class="bi bi-table"></i></div>
                        Struktur Kolom Tabel Rekap Nilai
                    </div>
                    <p class="text-muted mb-3" style="font-size:13px">Tabel rekap nilai pada halaman <strong>Nilai per Mata Kuliah</strong> menampilkan alur penilaian dari kiri ke kanan secara lengkap dan transparan:</p>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0" style="font-size:12px">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">Kolom</th>
                                    <th>Nama</th>
                                    <th class="text-center">Bobot / Keterangan</th>
                                    <th>Penjelasan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $cols = [
                                        ['Hadir','25%','Persentase kehadiran riil (jumlah hadir ÷ total pertemuan × 100)','#eff6ff','#1a4a7a'],
                                        ['Keaktifan','Bonus 10%','Skor keaktifan rata-rata dari checklist indikator per pertemuan','#f0fdf4','#16a34a'],
                                        ['Tugas','20%','Rata-rata nilai tugas yang diinput (murni, tanpa modifikasi)','#eff6ff','#0891b2'],
                                        ['UTS','25%','Nilai Ujian Tengah Semester','#faf5ff','#7c3aed'],
                                        ['UAS','30%','Nilai Ujian Akhir Semester','#fff5f5','#dc2626'],
                                        ['NA Murni','100%','Hasil perhitungan bobot 100% dari Hadir+Tugas+UTS+UAS','#f8fafc','#475569'],
                                        ['Bonus','+Keaktifan × 10%','Bonus aktual yang diperoleh mahasiswa dari keaktifan','#f0fdf4','#16a34a'],
                                        ['Nilai Finish','= NA Murni + Bonus','Nilai gabungan setelah ditambah bonus, dibatasi maksimal 100','#eff6ff','#1d4ed8'],
                                        ['NA','= Nilai Finish','Nilai Akhir resmi yang dicatat sistem','#fef3c7','#d97706'],
                                        ['Mutu','A/B/C/D/E','Huruf mutu konversi dari Nilai Akhir','#fef3c7','#d97706'],
                                        ['Status','Lulus / Tidak Lulus','Status kelulusan berdasarkan kehadiran dan nilai akhir','#f0fdf4','#16a34a'],
                                    ];
                                @endphp
                                @foreach($cols as $i => $c)
                                <tr style="background:{{ $c[3] }}">
                                    <td class="text-center fw-bold text-muted">{{ $i+1 }}</td>
                                    <td><strong style="color:{{ $c[4] }}">{{ $c[0] }}</strong></td>
                                    <td class="text-center"><code style="font-size:11px;background:{{ $c[3] }};color:{{ $c[4] }}">{{ $c[1] }}</code></td>
                                    <td style="color:#374151">{{ $c[2] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 4. ALUR KERJA SISTEM --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <div class="sec-title">
                        <div class="icon-box" style="background:#faf5ff;color:#7c3aed"><i class="bi bi-diagram-3-fill"></i></div>
                        Alur Kerja Sistem (Workflow)
                    </div>
                    <div class="tl">
                        @php
                            $steps = [
                                ['bg'=>'#1a4a7a','icon'=>'bi-building','title'=>'1. Setup Master Data','desc'=>'Admin menambahkan data Kampus, Kelas, Mata Kuliah, dan Mahasiswa. Dosen didaftarkan ke sistem dengan role yang sesuai.'],
                                ['bg'=>'#0891b2','icon'=>'bi-person-plus','title'=>'2. Pendaftaran KRS','desc'=>'Mahasiswa didaftarkan ke dalam Mata Kuliah agar masuk ke daftar absensi dan penilaian. Bisa dilakukan via menu Mata Kuliah → Peserta.'],
                                ['bg'=>'#7c3aed','icon'=>'bi-calendar-check','title'=>'3. Absensi Harian','desc'=>'Dosen mencatat kehadiran per pertemuan (Hadir, Izin, Sakit, Alpa). Sistem merekap persentase kehadiran secara otomatis.'],
                                ['bg'=>'#059669','icon'=>'bi-star','title'=>'4. Penilaian Keaktifan','desc'=>'Dosen mengisi skor keaktifan per pertemuan via form checklist indikator. Sistem menghitung rata-rata keaktifan secara otomatis.'],
                                ['bg'=>'#d97706','icon'=>'bi-journal-text','title'=>'5. Input Nilai Tugas','desc'=>'Dosen menambahkan kolom tugas secara dinamis dan mengisi nilai per mahasiswa. Sistem menghitung rata-rata tugas otomatis.'],
                                ['bg'=>'#dc2626','icon'=>'bi-pencil-square','title'=>'6. Input UTS & UAS','desc'=>'Dosen menginput nilai UTS dan UAS di form Input Teori. Sistem menghitung NA Murni, Bonus Keaktifan, dan Nilai Finish secara real-time.'],
                                ['bg'=>'#1a4a7a','icon'=>'bi-arrow-clockwise','title'=>'7. Kalkulasi Ulang (opsional)','desc'=>'Tombol Kalkulasi Ulang tersedia untuk menyinkronkan seluruh nilai mahasiswa ke rumus terbaru jika ada perubahan aturan bobot.'],
                                ['bg'=>'#16a34a','icon'=>'bi-file-earmark-pdf','title'=>'8. Laporan & Ekspor','desc'=>'Pengelola mencetak rekap nilai dalam format PDF atau mengunduh dalam format Excel. Laporan transkrip dan rekap kampus juga tersedia.'],
                            ];
                        @endphp
                        @foreach($steps as $s)
                        <div class="tl-item">
                            <div class="tl-dot" style="background:{{ $s['bg'] }}">
                                <i class="bi {{ $s['icon'] }}" style="font-size:12px"></i>
                            </div>
                            <h6>{{ $s['title'] }}</h6>
                            <p>{{ $s['desc'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        {{-- ═══════════════════════════════════════════
             KOLOM KANAN (4 kolom)
        ═══════════════════════════════════════════ --}}
        <div class="col-lg-4">

            {{-- Bobot Aktif dari DB --}}
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-sliders text-primary"></i>
                    Bobot Aktif Saat Ini
                </div>
                <div class="card-body p-3">
                    @php
                        $bobotItems = [
                            ['Kehadiran', $bobot->kehadiran??25, '#1a4a7a'],
                            ['Tugas',     $bobot->tugas??20,     '#0891b2'],
                            ['UTS',       $bobot->uts??25,       '#7c3aed'],
                            ['UAS',       $bobot->uas??30,       '#dc2626'],
                        ];
                    @endphp
                    @foreach($bobotItems as $bi)
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span style="font-size:13px;font-weight:600;color:#374151">{{ $bi[0] }}</span>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:80px;height:6px;background:#e2e8f0;border-radius:3px;overflow:hidden">
                                <div style="height:100%;width:{{ $bi[1] }}%;background:{{ $bi[2] }};border-radius:3px"></div>
                            </div>
                            <span class="fw-bold" style="font-size:14px;color:{{ $bi[2] }};min-width:36px;text-align:right">{{ $bi[1] }}%</span>
                        </div>
                    </div>
                    @endforeach
                    <hr class="my-2">
                    <div class="d-flex justify-content-between">
                        <span style="font-size:12px;color:#64748b">Bonus Keaktifan</span>
                        <span class="fw-bold text-success">+ Keaktifan × 10%</span>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('bobot.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i>Ubah Bobot
                        </a>
                    </div>
                </div>
            </div>

            {{-- Contoh Hitung --}}
            <div class="card mb-4" style="border-color:#bfdbfe">
                <div class="card-header" style="background:#eff6ff;border-color:#bfdbfe;color:#1a4a7a">
                    <i class="bi bi-calculator-fill me-1"></i>Contoh Perhitungan
                </div>
                <div class="card-body p-3">
                    <div style="font-size:11px;color:#94a3b8;font-weight:700;text-transform:uppercase;margin-bottom:8px">Input Nilai</div>
                    @php
                        $hHadir = 87.5; $hTugas = 93.33; $hUts = 63; $hUas = 80; $hKeaktifan = 58.13;
                        $hNaMurni = round(($hHadir*0.25)+($hTugas*0.20)+($hUts*0.25)+($hUas*0.30), 2);
                        $hBonus = round($hKeaktifan*0.10, 2);
                        $hFinish = min(100, round($hNaMurni + $hBonus, 2));
                    @endphp
                    <table class="table table-sm mb-2" style="font-size:12px">
                        <tr><td class="text-muted">Hadir</td><td class="text-end fw-bold">{{ $hHadir }}%</td></tr>
                        <tr><td class="text-muted">Tugas</td><td class="text-end fw-bold">{{ $hTugas }}</td></tr>
                        <tr><td class="text-muted">UTS</td><td class="text-end fw-bold">{{ $hUts }}</td></tr>
                        <tr><td class="text-muted">UAS</td><td class="text-end fw-bold">{{ $hUas }}</td></tr>
                        <tr><td class="text-muted">Keaktifan</td><td class="text-end fw-bold">{{ $hKeaktifan }}</td></tr>
                    </table>
                    <hr class="my-2">
                    <div style="font-size:11px;color:#94a3b8;font-weight:700;text-transform:uppercase;margin-bottom:8px">Hasil</div>
                    <table class="table table-sm mb-0" style="font-size:12px">
                        <tr>
                            <td class="text-muted">NA Murni</td>
                            <td class="text-end"><span class="fw-bold">{{ $hNaMurni }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Bonus</td>
                            <td class="text-end"><span class="badge bg-success-subtle text-success border border-success-subtle">+{{ $hBonus }}</span></td>
                        </tr>
                        <tr style="background:#eff6ff">
                            <td class="fw-bold" style="color:#1a4a7a">Nilai Finish</td>
                            <td class="text-end fw-bold" style="color:#1d4ed8;font-size:16px">{{ $hFinish }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Huruf Mutu</td>
                            <td class="text-end">
                                <span class="badge badge-{{ strtolower($hFinish >= 85 ? 'a' : ($hFinish >= 75 ? 'b' : ($hFinish >= 65 ? 'c' : ($hFinish >= 55 ? 'd' : 'e')))) }} fs-6">
                                    {{ $hFinish >= 85 ? 'A' : ($hFinish >= 75 ? 'B' : ($hFinish >= 65 ? 'C' : ($hFinish >= 55 ? 'D' : 'E'))) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Fitur Utama --}}
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-grid-fill text-primary me-1"></i>Fitur Utama</div>
                <div class="card-body p-3">
                    @php
                        $features = [
                            ['bi-building','#1a4a7a','#eff6ff','Multi-Kampus','Kelola lebih dari satu kampus dalam satu sistem'],
                            ['bi-calendar-check','#059669','#f0fdf4','Absensi Harian','Rekap otomatis persentase kehadiran per mahasiswa'],
                            ['bi-star','#d97706','#fef3c7','Keaktifan Bonus','Checklist indikator keaktifan per pertemuan dengan bonus hingga 10%'],
                            ['bi-journal-text','#7c3aed','#faf5ff','Tugas Dinamis','Tambah/hapus kolom tugas secara bebas, rata-rata otomatis'],
                            ['bi-calculator','#0891b2','#ecfeff','Kalkulasi Otomatis','NA Murni + Bonus + Nilai Finish dihitung real-time'],
                            ['bi-file-earmark-pdf','#dc2626','#fff5f5','Ekspor PDF & Excel','Cetak laporan rekap, transkrip, dan absensi'],
                            ['bi-arrow-clockwise','#1a4a7a','#eff6ff','Kalkulasi Ulang','Sinkronkan semua nilai ke rumus terbaru kapan saja'],
                            ['bi-bell-fill','#d97706','#fef3c7','Notifikasi Berisiko','Alert otomatis mahasiswa dengan kehadiran rendah'],
                        ];
                    @endphp
                    @foreach($features as $f)
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div style="width:34px;height:34px;border-radius:9px;background:{{ $f[2] }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="bi {{ $f[0] }}" style="color:{{ $f[1] }};font-size:15px"></i>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:700;color:#1e293b">{{ $f[3] }}</div>
                            <div style="font-size:11.5px;color:#64748b;line-height:1.5">{{ $f[4] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Tech Stack --}}
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-code-slash text-primary me-1"></i>Teknologi</div>
                <div class="card-body p-3">
                    @php
                        $techs = [
                            ['bi-box','Laravel 11+','Backend Framework'],
                            ['bi-filetype-php','PHP 8.2+','Bahasa Server'],
                            ['bi-bootstrap','Bootstrap 5','UI Framework'],
                            ['bi-database','MySQL / MariaDB','Database'],
                            ['bi-file-earmark-pdf','DomPDF','Ekspor PDF'],
                            ['bi-file-earmark-excel','Maatwebsite Excel','Ekspor Excel'],
                        ];
                    @endphp
                    @foreach($techs as $t)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi {{ $t[0] }} text-primary" style="font-size:15px;width:18px"></i>
                        <span style="font-size:13px;font-weight:600;color:#1e293b">{{ $t[1] }}</span>
                        <span class="ms-auto" style="font-size:11px;color:#94a3b8">{{ $t[2] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="card">
                <div class="card-header"><i class="bi bi-lightning-fill text-warning me-1"></i>Akses Cepat</div>
                <div class="card-body p-3">
                    <a href="{{ route('nilai.pilih') }}" class="btn btn-sm btn-outline-primary w-100 mb-2 text-start">
                        <i class="bi bi-clipboard-data me-2"></i>Input / Lihat Nilai
                    </a>
                    <a href="{{ route('absensi.pilih') }}" class="btn btn-sm btn-outline-success w-100 mb-2 text-start">
                        <i class="bi bi-calendar-check me-2"></i>Absensi Mahasiswa
                    </a>
                    <a href="{{ route('laporan.rekap') }}" class="btn btn-sm btn-outline-info w-100 mb-2 text-start">
                        <i class="bi bi-bar-chart-line me-2"></i>Rekap & Laporan
                    </a>
                    <a href="{{ route('bobot.index') }}" class="btn btn-sm btn-outline-warning w-100 text-start">
                        <i class="bi bi-sliders me-2"></i>Pengaturan Bobot Nilai
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
