<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem Penilaian</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a4a7a, #0f2d50);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 24px 64px rgba(0, 0, 0, .35);
            max-width: 440px;
            width: 100%
        }

        .card-body {
            padding: 40px 36px
        }

        .logo {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            background: linear-gradient(135deg, #1a4a7a, #2563eb);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 26px;
            margin: auto auto 20px
        }

        .form-control {
            border-radius: 8px
        }

        .btn-login {
            background: #1a4a7a;
            border: none;
            border-radius: 8px
        }

        .kampus-card {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px;
            cursor: pointer;
            display: flex;
            gap: 10px
        }

        .kampus-card.selected {
            border-color: #1a4a7a;
            background: #eff6ff
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-body">

            <div class="logo">
                <i class="bi bi-mortarboard-fill"></i>
            </div>

            <h4 class="text-center fw-bold">Sistem Penilaian</h4>
            <p class="text-center text-muted small mb-4">ITBM & STAIN Majene</p>

            {{-- Error --}}
            @if (!empty($errors) && $errors->any())
                <div class="alert alert-danger small">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        required>
                </div>

                {{-- Kampus
                @if (isset($kampusList) && count($kampusList) > 0)
                    <div class="mb-3">
                        <small class="text-muted">Pilih Kampus (opsional)</small>

                        @foreach ($kampusList as $k)
                            <label class="kampus-card w-100 mb-2">
                                <input type="radio" name="kampus_id" value="{{ $k->id }}">
                                <div>
                                    <div>{{ $k->kode }}</div>
                                    <small>
                                        {{ \Illuminate\Support\Str::limit($k->nama, 28) }}
                                    </small>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endif --}}

                <div class="form-check mb-3">
                    <input type="checkbox" name="remember" class="form-check-input">
                    <label class="form-check-label">Ingat saya</label>
                </div>

                <button class="btn btn-login w-100 text-white">
                    Masuk
                </button>

            </form>
        </div>
    </div>

</body>

</html>
