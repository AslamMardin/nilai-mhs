@extends('layouts.app')
@section('title','Daftar Mata Kuliah')
@section('page-title','Mahasiswa · Daftar Mata Kuliah')
@section('content')
<div class="row justify-content-center">
  <div class="col-lg-7">

    <div class="card mb-3">
      <div class="card-body py-2 d-flex align-items-center gap-3">
        <div style="width:44px;height:44px;border-radius:50%;background:#1a4a7a;color:#fff;font-size:18px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0">
          {{ strtoupper(substr($mahasiswa->nama,0,1)) }}
        </div>
        <div class="flex-grow-1">
          <div class="fw-700">{{ $mahasiswa->nama }}</div>
          <code class="small text-muted">{{ $mahasiswa->nim }}</code>
          <span class="badge bg-light text-dark border ms-1">{{ $mahasiswa->kampus->kode }}</span>
          <span class="badge bg-light text-dark border ms-1">{{ $mahasiswa->kelas->nama }}</span>
        </div>
        <a href="{{ route('mahasiswa.show',$mahasiswa->id) }}" class="btn btn-sm btn-outline-secondary">
          <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <i class="bi bi-book text-primary me-1"></i>Kelola Pendaftaran Mata Kuliah
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('mahasiswa.simpan-daftar',$mahasiswa->id) }}">
          @csrf
          <div class="row g-3 mb-4">
            <div class="col-6">
              <label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
              <input type="number" name="tahun_ajaran" class="form-control"
                     value="{{ old('tahun_ajaran', date('Y')) }}" min="2000" max="2099" required>
            </div>
            <div class="col-6">
              <label class="form-label">Semester <span class="text-danger">*</span></label>
              <select name="semester" class="form-select" required>
                <option value="ganjil" {{ old('semester','ganjil')=='ganjil'?'selected':'' }}>Ganjil</option>
                <option value="genap"  {{ old('semester')=='genap'?'selected':'' }}>Genap</option>
              </select>
            </div>
          </div>

          <div class="mb-3 d-flex justify-content-between align-items-center">
            <span class="small text-muted fw-500">Pilih mata kuliah yang diikuti:</span>
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-sm btn-outline-primary py-0" id="btn-all">
                <i class="bi bi-check-all"></i> Semua
              </button>
              <button type="button" class="btn btn-sm btn-outline-secondary py-0" id="btn-none">
                Hapus
              </button>
            </div>
          </div>

          @php $grouped = $mataKuliah->groupBy('kelas_id'); @endphp
          @foreach($grouped as $kelasId => $mkList)
          @php $kls = $mkList->first()->kelas; @endphp
          <div class="mb-3">
            <div class="text-muted small fw-600 mb-2 pb-1 border-bottom">
              <i class="bi bi-door-open me-1"></i>{{ $kls->kode }} — {{ $kls->nama }}
            </div>
            <div class="d-flex flex-column gap-2">
              @foreach($mkList as $mk)
              <label class="d-flex align-items-start gap-3 p-3 border rounded-2 matkul-item {{ in_array($mk->id,$terdaftar)?'border-primary':'' }}"
                     style="cursor:pointer;transition:.15s" id="lbl-{{ $mk->id }}">
                <input type="checkbox" name="mata_kuliah_ids[]" value="{{ $mk->id }}"
                       class="form-check-input mt-1 flex-shrink-0 matkul-chk"
                       style="width:18px;height:18px;accent-color:#1a4a7a"
                       {{ in_array($mk->id,$terdaftar)?'checked':'' }}>
                <div class="flex-grow-1">
                  <div class="d-flex align-items-center gap-2 flex-wrap">
                    <code class="small">{{ $mk->kode }}</code>
                    <span class="fw-500">{{ $mk->nama }}</span>
                  </div>
                  <div class="d-flex gap-2 mt-1 flex-wrap">
                    <span class="badge {{ $mk->jenis=='teori'?'bg-info text-dark':($mk->jenis=='praktikum'?'bg-success':'bg-primary') }}" style="font-size:10px">
                      {{ $mk->label_jenis }}
                    </span>
                    <span class="badge bg-light text-dark border" style="font-size:10px">{{ $mk->sks }} SKS</span>
                    @if($mk->dosen)
                    <span class="text-muted" style="font-size:11px">
                      <i class="bi bi-person me-1"></i>{{ $mk->dosen }}
                    </span>
                    @endif
                  </div>
                </div>
                @if(in_array($mk->id,$terdaftar))
                <i class="bi bi-check-circle-fill text-primary fs-5 mt-1 flex-shrink-0"></i>
                @endif
              </label>
              @endforeach
            </div>
          </div>
          @endforeach

          @if($mataKuliah->isEmpty())
          <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-1"></i>
            Belum ada mata kuliah di kampus ini.
            <a href="{{ route('matakuliah.create') }}">Tambah mata kuliah</a>.
          </div>
          @endif

          <div class="d-flex gap-2 mt-4">
            <a href="{{ route('mahasiswa.show',$mahasiswa->id) }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-save me-1"></i>Simpan Pendaftaran
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
document.querySelectorAll('.matkul-chk').forEach(chk => {
  chk.addEventListener('change', function() {
    const lbl = document.getElementById('lbl-' + this.value);
    lbl.classList.toggle('border-primary', this.checked);
  });
});
document.getElementById('btn-all')?.addEventListener('click', () => {
  document.querySelectorAll('.matkul-chk').forEach(c => {
    c.checked = true;
    document.getElementById('lbl-'+c.value)?.classList.add('border-primary');
  });
});
document.getElementById('btn-none')?.addEventListener('click', () => {
  document.querySelectorAll('.matkul-chk').forEach(c => {
    c.checked = false;
    document.getElementById('lbl-'+c.value)?.classList.remove('border-primary');
  });
});
</script>
@endpush
