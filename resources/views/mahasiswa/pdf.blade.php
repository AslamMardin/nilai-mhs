<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body { font-family: sans-serif; font-size: 10px; }
table { width: 100%; border-collapse: collapse; margin-top: 10px; }
th, td { border: 1px solid #000; padding: 4px; text-align: center; }
th { background: #eee; }
.title { text-align: center; font-size: 16px; margin-bottom: 10px; }
.text-left { text-align: left; }
</style>
</head>
<body>

<div class="title">
  <strong>DATA MAHASISWA</strong>
</div>

<table>
  <thead>
    <tr>
      <th>No</th>
      <th>NIM</th>
      <th>Nama</th>
      <th>Kampus</th>
      <th>Kelas</th>
      <th>JK</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    @foreach($mahasiswa as $i => $mhs)
    <tr>
      <td>{{ $i+1 }}</td>
      <td>{{ $mhs->nim }}</td>
      <td class="text-left">{{ $mhs->nama }}</td>
      <td>{{ $mhs->kampus->kode ?? '-' }}</td>
      <td>{{ $mhs->kelas->nama ?? '-' }}</td>
      <td>{{ $mhs->jenis_kelamin == 'L' ? 'L' : 'P' }}</td>
      <td>{{ ucfirst($mhs->status) }}</td>
    </tr>
    @endforeach
  </tbody>
</table>

</body>
</html>
