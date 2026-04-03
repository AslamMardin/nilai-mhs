@extends('layouts.app')
@section('title','Laporan Nilai per Kelas')
@section('page-title','Laporan · Nilai per Kelas')
@section('content')
<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-4">
        <label class="form-label mb-1 small">Pilih Kelas</label>
        <select name="kelas_id" class="form-select" onchange="this.form.submit()">
          <option value="">-- Pilih Kelas --</option>
          @foreach($kelasList as $kls)
          <option value="{{ $kls->id }}" {{ $kelasId==$kls->id?'selected':'' }}>
            [{{ $kls->kampus->kode }}] {{ $kls->nama }} — {{ ucfirst($kls->semester) }} {{ $kls->tahun_ajaran }}
          </option>
          @endforeach
        </select>
      </div>
      @if($kelasId)
      <div class="col-md-8">
        <div class="btn-group">

          <button onclick="window.print()" type="button" class="btn btn-outline-secondary">
            <i class="bi bi-printer me-1"></i>Cetak
          </button>
          <a href="{{ route('laporan.nilai-kelas.excel', $kelasId) }}" 
   class="btn btn-success"
   onclick="showLoading()">
  <i class="bi bi-file-earmark-excel"></i> Export Excel
</a>
<a href="{{ route('laporan.nilai-kelas.pdf', $kelasId) }}" 
   target="_blank"
   class="btn btn-danger">
  <i class="bi bi-file-earmark-pdf"></i> Export PDF
</a>
      </div>
      </div>
      
      @endif
    </form>
  </div>
</div>

@if($kelasId && $kelasSelected)
<div class="card mb-3">
  <div class="card-body py-2 d-flex gap-4">
    <div><span class="text-muted small">Kelas:</span><br><strong>{{ $kelasSelected->nama }}</strong></div>
    <div><span class="text-muted small">Kampus:</span><br><strong>{{ $kelasSelected->kampus->kode }}</strong></div>
    <div><span class="text-muted small">Semester:</span><br><strong>{{ ucfirst($kelasSelected->semester) }} {{ $kelasSelected->tahun_ajaran }}</strong></div>
    <div><span class="text-muted small">Mahasiswa:</span><br><strong>{{ $kelasSelected->mahasiswa->count() }}</strong></div>
  </div>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-bordered table-sm mb-0" style="font-size:12px">
        <thead class="table-light">
          <tr>
            <th class="ps-3" rowspan="2">#</th>
            <th rowspan="2">NIM / Nama</th>
            @foreach($kelasSelected->mataKuliah as $mk)
            <th class="text-center" colspan="3" style="font-size:10px">{{ $mk->kode }}</th>
            @endforeach
            <th class="text-center" rowspan="2">Rata-rata</th>
          </tr>
          <tr>
            @foreach($kelasSelected->mataKuliah as $mk)
            <th class="text-center" style="font-size:10px">NA</th>
            <th class="text-center" style="font-size:10px">Mutu</th>
            <th class="text-center" style="font-size:10px">Status</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @forelse($data as $i => $row)
          <tr>
            <td class="ps-3 text-muted">{{ $i+1 }}</td>
            <td>
              <div class="fw-500">{{ $row['mahasiswa']->nama }}</div>
              <code style="font-size:10px;color:#64748b">{{ $row['mahasiswa']->nim }}</code>
            </td>
            @foreach($kelasSelected->mataKuliah as $mk)
            @php $n = $row['nilai'][$mk->kode] ?? null; @endphp
            <td class="text-center fw-700">{{ $n['nilai_akhir'] ?? '—' }}</td>
            <td class="text-center">
              @if(isset($n['huruf_mutu']) && $n['huruf_mutu']!='—')
              <span class="badge badge-{{ strtolower($n['huruf_mutu']) }}">{{ $n['huruf_mutu'] }}</span>
              @else—@endif
            </td>
            <td class="text-center">
              @if(isset($n['status_kelulusan']))
                @if($n['status_kelulusan']=='lulus')<i class="bi bi-check-circle-fill text-success"></i>
                @elseif($n['status_kelulusan']=='tidak_lulus')<i class="bi bi-x-circle-fill text-danger"></i>
                @else<i class="bi bi-dash-circle text-secondary"></i>@endif
              @else —@endif
            </td>
            @endforeach
            <td class="text-center fw-700 text-primary">{{ number_format($row['rata_rata'],2) }}</td>
          </tr>
          @empty
          <tr><td colspan="20" class="text-center text-muted py-4">Belum ada data.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@else
<div class="text-center py-5 text-muted">
  <i class="bi bi-table fs-1 d-block mb-2 opacity-25"></i>
  Pilih kelas untuk melihat laporan nilai.
</div>
@endif
@endsection
