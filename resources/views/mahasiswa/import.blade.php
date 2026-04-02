@extends('layouts.app')

@section('title','Import Mahasiswa')
@section('page-title','Import Data Mahasiswa')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-6">

    <div class="card mb-3">
      <div class="card-body">

        <h5>Import Data Mahasiswa</h5>

        <div class="alert alert-info small">
          <b>Perhatian:</b><br>
          - Gunakan format file Excel sesuai template<br>
          - Jenis kelamin: <b>L</b> / <b>P</b><br>
          - Kampus akan otomatis dari session<br>
          - Pastikan NIM tidak duplikat
        </div>

        {{-- Download template --}}
        <a href="{{ route('mahasiswa.template') }}" class="btn btn-success mb-3">
          Download Template
        </a>


        {{-- Form upload --}}
        <form action="{{ route('mahasiswa.import.process') }}" 
      method="POST" 
      enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
  <label>Kampus</label>
  <select name="kampus_id" class="form-select" required>
    <option value="">-- Pilih Kampus --</option>
    @foreach($kampusList as $k)
      <option value="{{ $k->id }}">{{ $k->nama ?? $k->kode }}</option>
    @endforeach
  </select>
</div>

<div class="mb-3">
  <label>Kelas</label>
  <select name="kelas_id" class="form-select" required>
    <option value="">-- Pilih Kelas --</option>
    @foreach($kelasList as $kls)
      <option value="{{ $kls->id }}">{{ $kls->nama ?? $kls->kode }} ({{$kls->mahasiswa_count}})</option>
    @endforeach
  </select>
</div>

          <div class="mb-3">
            <label>Upload File Excel</label>
            <input type="file" name="file" class="form-control" required>
          </div>

             <button type="submit" id="btnImport" class="btn btn-primary w-100">
        <span id="textBtn">Import Sekarang</span>
        <span id="loadingBtn" class="d-none">
            <span class="spinner-border spinner-border-sm"></span> Memproses...
        </span>
    </button>

        </form>

        <div id="progressWrapper" class="d-none mt-3">
    <div class="progress">
        <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%">0%</div>
    </div>
</div>

      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
    <script>
const form = document.querySelector('form');
const btnText = document.getElementById('textBtn');
const btnLoading = document.getElementById('loadingBtn');
const progressWrapper = document.getElementById('progressWrapper');
const progressBar = document.getElementById('progressBar');

form.addEventListener('submit', function () {

    btnText.classList.add('d-none');
    btnLoading.classList.remove('d-none');

    progressWrapper.classList.remove('d-none');

    let width = 0;

    let interval = setInterval(() => {
        if (width >= 90) {
            clearInterval(interval);
        } else {
            width += 5;
            progressBar.style.width = width + '%';
            progressBar.innerText = width + '%';
        }
    }, 200);
});
</script>
@endpush