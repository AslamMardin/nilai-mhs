@extends('layouts.app')

@section('title','Backup Database')
@section('page-title','Backup Database')

@section('content')


@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif
<div class="card mb-3">
    <div class="card-body text-center">

        <h5 class="mb-3">📦 Backup Database Sistem</h5>

        <form method="POST" action="{{ route('backup.run') }}">
            @csrf
            <button class="btn btn-success">
                <i class="bi bi-download"></i>
                Buat Backup Sekarang
            </button>
        </form>

    </div>
</div>

<div class="card">
    <div class="card-header">
        Riwayat Backup
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama File</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($backups as $file)
                <tr>
                    <td>{{ $file }}</td>
                    <td>
                        <a href="{{ route('backup.download',$file) }}" 
                           class="btn btn-sm btn-primary">
                           Download
                        </a>

                        <form method="POST" 
                              action="{{ route('backup.delete',$file) }}"
                              style="display:inline-block"
                              onsubmit="return confirm('Hapus file backup ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center text-muted">
                        Belum ada backup
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection