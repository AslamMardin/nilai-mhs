<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Pilih Kampus</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
body{min-height:100vh;background:linear-gradient(135deg,#1a4a7a,#0f2d50);display:flex;align-items:center;justify-content:center;padding:20px}
.wrap{width:100%;max-width:560px}
.kc{border:2px solid #e2e8f0;border-radius:14px;padding:20px;cursor:pointer;transition:.2s;background:#fff}
.kc:hover{border-color:#1a4a7a;transform:translateY(-2px);box-shadow:0 8px 24px rgba(26,74,122,.12)}
.kc.selected{border-color:#1a4a7a;background:#eff6ff}
.kc .kode{font-size:22px;font-weight:800;color:#1a4a7a}
.kc .stat{font-size:12px;color:#64748b}
</style>
</head>
<body>
<div class="wrap">
  <div class="text-center text-white mb-4">
    <i class="bi bi-building fs-1 mb-2 d-block opacity-75"></i>
    <h4 class="fw-bold">Pilih Kampus</h4>
    <p class="opacity-75 small">Pilih kampus yang ingin Anda kelola</p>
  </div>

  <form method="POST" action="{{ route('simpan-kampus') }}">
    @csrf
    @if($errors->any())
    <div class="alert alert-danger py-2 small mb-3">{{ $errors->first() }}</div>
    @endif

    <div class="row g-3 mb-4">
      @foreach($kampusList as $k)
      <div class="col-md-6">
        <label class="kc w-100 d-block" id="kc-{{ $k->id }}">
          <input type="radio" name="kampus_id" value="{{ $k->id }}" class="d-none kc-r" required>
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kode">{{ $k->kode }}</div>
              <div class="fw-500 mt-1" style="font-size:13px">{{ $k->nama }}</div>
              @if($k->alamat)<div class="text-muted small mt-1"><i class="bi bi-geo-alt me-1"></i>{{ $k->alamat }}</div>@endif
            </div>
            <i class="bi bi-check-circle-fill text-primary fs-4" style="display:none" id="chk-{{ $k->id }}"></i>
          </div>
          <div class="d-flex gap-3 mt-2 pt-2 border-top">
            <span class="stat"><i class="bi bi-door-open me-1"></i>{{ $k->kelas_count }} Kelas</span>
            <span class="stat"><i class="bi bi-people me-1"></i>{{ $k->mahasiswa_count }} Mahasiswa</span>
            <span class="stat"><i class="bi bi-book me-1"></i>{{ $k->mata_kuliah_count }} Matkul</span>
          </div>
        </label>
      </div>
      @endforeach
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2 fw-600">
      <i class="bi bi-arrow-right-circle me-1"></i> Masuk ke Kampus Terpilih
    </button>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.kc-r').forEach(r=>{
  r.addEventListener('change',()=>{
    document.querySelectorAll('label[id^="kc-"]').forEach(l=>{
      l.classList.remove('selected');
      const chk=document.getElementById('chk-'+l.id.replace('kc-',''));
      if(chk) chk.style.display='none';
    });
    r.closest('label').classList.add('selected');
    const id=r.value;
    document.getElementById('chk-'+id).style.display='';
  });
});
</script>
</body>
</html>
