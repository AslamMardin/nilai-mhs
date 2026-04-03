<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body { font-family: sans-serif; font-size: 10px; }
table { width: 100%; border-collapse: collapse; }
th, td { border: 1px solid #000; padding: 4px; text-align: center; }
th { background: #eee; }
.title { text-align: center; font-size: 16px; margin-bottom: 10px; }
.info { margin-bottom: 10px; }
</style>
</head>
<body>

<div class="title">
  <strong>LAPORAN NILAI KELAS</strong>
</div>

<div class="info">
  <strong>Kelas:</strong> {{ $kelas->nama }} <br>
  <strong>Kampus:</strong> {{ $kelas->kampus->kode }} <br>
  <strong>Jumlah Mahasiswa:</strong> {{ $kelas->mahasiswa->count() }}
</div>

<table>
  <thead>
    <tr>
      <th>No</th>
      <th>NIM</th>
      <th>Nama</th>
      @foreach($kelas->mataKuliah as $mk)
        <th>{{ $mk->kode }}</th>
      @endforeach
      <th>Rata-rata</th>
    </tr>
  </thead>
  <tbody>
    @foreach($kelas->mahasiswa as $i => $mhs)
    @php
      $nilaiList = [];
    @endphp
    <tr>
      <td>{{ $i+1 }}</td>
      <td>{{ $mhs->nim }}</td>
      <td style="text-align:left">{{ $mhs->nama }}</td>

      @foreach($kelas->mataKuliah as $mk)
        @php
          $na = \App\Models\NilaiAkhir::where('mahasiswa_id',$mhs->id)
                ->where('mata_kuliah_id',$mk->id)->first();

          $nilaiList[] = is_numeric($na?->nilai_akhir) ? $na->nilai_akhir : null;
        @endphp
        <td>{{ $na?->nilai_akhir ?? '-' }}</td>
      @endforeach

      <td>
        {{ round(collect($nilaiList)->filter()->avg() ?? 0, 2) }}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

</body>
</html>