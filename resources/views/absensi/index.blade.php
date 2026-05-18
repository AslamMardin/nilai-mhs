@extends('layouts.app')
@section('title','Input Absensi')
@section('page-title','Absensi · ' . $mataKuliah->nama)
@section('content')

{{-- Header Matkul --}}
<div class="card mb-3">
  <div class="card-body py-2 d-flex flex-wrap align-items-center gap-3">
    <div class="flex-grow-1">
      <span class="fw-700 fs-6">{{ $mataKuliah->nama }}</span>
      <span class="badge bg-light text-dark border ms-2">{{ $mataKuliah->kode }}</span>
      <div class="text-muted small mt-1">{{ $mataKuliah->kampus->kode }} · {{ $mataKuliah->kelas->nama }} · Dosen: {{ $mataKuliah->dosen??'—' }} · {{ $mataKuliah->total_pertemuan }} Pertemuan</div>
    </div>
    <a href="{{ route('absensi.rekap',$mataKuliah->id) }}" class="btn btn-outline-info btn-sm">
      <i class="bi bi-table me-1"></i>Rekap Kehadiran
    </a>
    <a href="{{ route('absensi.pilih') }}" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
  </div>
</div>

{{-- Keterangan Bobot --}}
<div class="alert alert-light border py-2 mb-3" style="font-size:12px">
  <strong>Bobot Kehadiran:</strong>
  <span class="sp sp-H ms-2">H</span> Hadir=2 &nbsp;
  <span class="sp sp-T">T</span> Terlambat=1 &nbsp;
  <span class="sp sp-S">S</span> Sakit=1 &nbsp;
  <span class="sp sp-I">I</span> Izin=0 &nbsp;
  <span class="sp sp-A">A</span> Absen=0 &nbsp;
  &nbsp;|&nbsp; <strong>Syarat Lulus:</strong> Kehadiran ≥ 75% dari total pertemuan
</div>

<div class="row g-3">
  {{-- Form Input Absensi --}}
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-pencil-square text-primary me-1"></i>Input Absensi per Pertemuan</span>
        <button type="button" class="btn btn-xs btn-outline-success btn-sm" id="btn-all-h">
          <i class="bi bi-check-all me-1"></i>Set Semua Hadir
        </button>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('absensi.simpan',$mataKuliah->id) }}" id="form-absensi">
          @csrf
          <div class="row g-3 mb-3">
            <div class="col-sm-4">
              <label class="form-label">Pertemuan Ke-</label>
              <select name="pertemuan_ke" class="form-select" id="sel-pertemuan">
                @for($i=1;$i<=$mataKuliah->total_pertemuan;$i++)
                <option value="{{ $i }}" {{ old('pertemuan_ke', request('pertemuan', 1)) == $i ? 'selected' : '' }}>Pertemuan {{ $i }}</option>
                @endfor
              </select>
            </div>
            <div class="col-sm-4">
              <label class="form-label">Tanggal</label>
              @php
                  $pilihPertemuan = old('pertemuan_ke', request('pertemuan', 1));
                  $defaultTanggal = isset($tanggalPertemuan[$pilihPertemuan]) 
                                    ? \Carbon\Carbon::parse($tanggalPertemuan[$pilihPertemuan])->format('Y-m-d') 
                                    : date('Y-m-d');
              @endphp
              <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $defaultTanggal) }}">
            </div>
            <div class="col-sm-4 d-flex align-items-end">
              <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save me-1"></i>Simpan</button>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
              <thead class="table-light">
                <tr><th class="ps-2" style="width:30px">#</th><th>NIM</th><th>Nama</th><th class="text-center">Status</th><th>Keterangan</th></tr>
              </thead>
              <tbody>
                @foreach($mataKuliah->mahasiswa as $idx => $mhs)
                @php
                  $pertemuan = old('pertemuan_ke', request('pertemuan', 1));
                  $existing  = $existingAbsensi["{$mhs->id}_{$pertemuan}"] ?? null;
                @endphp
                <input type="hidden" name="absensi[{{ $idx }}][mahasiswa_id]" value="{{ $mhs->id }}">
                <tr>
                  <td class="ps-2 text-muted small">{{ $idx+1 }}</td>
                  <td><code class="small">{{ $mhs->nim }}</code></td>
                  <td class="small">{{ $mhs->nama }}</td>
                  <td class="text-center">
                    <div class="d-flex gap-1 justify-content-center flex-wrap">
                      @foreach(['H'=>'success','T'=>'warning','S'=>'info','I'=>'secondary','A'=>'danger'] as $st=>$cls)
                      <div>
                        <input type="radio" class="btn-check absensi-radio" autocomplete="off"
                               name="absensi[{{ $idx }}][status]"
                               id="s{{ $mhs->id }}_{{ $st }}"
                               value="{{ $st }}"
                               {{ old("absensi.{$idx}.status",$existing?->status??'H')===$st?'checked':'' }}>
                        <label class="btn btn-outline-{{ $cls }} btn-sm px-2 py-1" for="s{{ $mhs->id }}_{{ $st }}" style="font-size:12px;min-width:36px">{{ $st }}</label>
                      </div>
                      @endforeach
                    </div>
                  </td>
                  <td>
                    <input type="text" name="absensi[{{ $idx }}][keterangan]" class="form-control form-control-sm"
                           value="{{ old("absensi.{$idx}.keterangan",$existing?->keterangan) }}" placeholder="Opsional">
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Rekap Kehadiran Ringkas --}}
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header"><i class="bi bi-person-check text-success me-1"></i>Rekap Kehadiran</div>
      <div class="card-body p-0">
        <div class="list-group list-group-flush">
          @foreach($mataKuliah->mahasiswa as $mhs)
          @php $rek=$rekapKehadiran[$mhs->id]??['persen'=>0,'poin'=>0,'lolos'=>false]; @endphp
          <div class="list-group-item px-3 py-2">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <div class="small fw-500">{{ $mhs->nama }}</div>
                <div class="text-muted" style="font-size:11px">Poin: {{ $rek['poin'] }}</div>
              </div>
              <span class="badge {{ $rek['lolos']?'bg-success':'bg-danger' }}">{{ $rek['persen'] }}%</span>
            </div>
            <div class="progress mt-1" style="height:4px;border-radius:4px">
              <div class="progress-bar {{ $rek['lolos']?'bg-success':'bg-danger' }}" style="width:{{ $rek['persen'] }}%"></div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Grid Absensi --}}
<div class="card mt-3">
  <div class="card-header"><i class="bi bi-grid-3x3 me-1 text-secondary"></i>Grid Absensi Semua Pertemuan</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-bordered table-sm text-center mb-0" style="font-size:12px">
        <thead class="table-light">
          <tr>
            <th class="text-start ps-2" style="min-width:130px">Mahasiswa</th>
            @for($p=1;$p<=$mataKuliah->total_pertemuan;$p++)
<th style="width:65px">
  <div class="fw-600">{{ $p }}</div>

  @if(isset($tanggalPertemuan[$p]))
    <div class="text-muted" style="font-size:10px">
      {{ \Carbon\Carbon::parse($tanggalPertemuan[$p])->format('d/m') }}
    </div>
  @else
    <div class="text-danger" style="font-size:10px">-</div>
  @endif
</th>
@endfor
            <th style="width:60px">%</th>
          </tr>
        </thead>
        <tbody>
          @foreach($mataKuliah->mahasiswa as $mhs)
          <tr>
            <td class="text-start ps-2 fw-500" style="font-size:11px">{{ $mhs->nama }}</td>
            @for($p=1;$p<=$mataKuliah->total_pertemuan;$p++)
            @php $a=$existingAbsensi["{$mhs->id}_{$p}"]??null; @endphp
            <td>
              @if($a)<span class="sp sp-{{ $a->status }}">{{ $a->status }}</span>
              @else<span class="text-muted">—</span>@endif
            </td>
            @endfor
            <td>
              @php $rek=$rekapKehadiran[$mhs->id]??['persen'=>0,'lolos'=>false]; @endphp
              <span class="badge {{ $rek['lolos']?'bg-success':'bg-danger' }}" style="font-size:10px">{{ $rek['persen'] }}%</span>
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
document.getElementById('btn-all-h')?.addEventListener('click',()=>{
  document.querySelectorAll('input[type=radio][value=H]').forEach(r=>r.checked=true);
});
// Memuat ulang halaman untuk mengambil data pertemuan yang dipilih dari server
document.getElementById('sel-pertemuan')?.addEventListener('change', function() {
  window.location.href = "?pertemuan=" + this.value;
});
</script>
@endpush
