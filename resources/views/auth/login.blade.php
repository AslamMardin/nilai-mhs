<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem Penilaian</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a4a7a 0%, #0f2d50 60%, #1a4a7a 100%);
            display: flex; align-items: center; justify-content: center;
        }
        .login-card {
            background: #fff; border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
            width: 100%; max-width: 420px; padding: 40px 36px;
        }
        .login-logo {
            width: 64px; height: 64px; border-radius: 14px;
            background: #1a4a7a; display: flex; align-items: center;
            justify-content: center; font-size: 28px; color: #fff; margin: 0 auto 20px;
        }
        .login-card h4 { font-weight: 700; color: #1e293b; text-align: center; margin-bottom: 4px; }
        .login-card p.sub { color: #64748b; font-size: 13px; text-align: center; margin-bottom: 28px; }
        .form-label { font-weight: 500; font-size: 13px; color: #374151; }
        .form-control { border-radius: 8px; font-size: 14px; padding: 10px 14px; }
        .form-control:focus { box-shadow: 0 0 0 3px rgba(26,74,122,.15); border-color: #1a4a7a; }
        .btn-login {
            background: #1a4a7a; border: none; border-radius: 8px;
            font-weight: 600; font-size: 14px; padding: 11px;
            width: 100%; color: #fff; transition: background .2s;
        }
        .btn-login:hover { background: #153d66; }
        .kampus-badge {
            display: flex; gap: 8px; justify-content: center; margin-top: 24px;
        }
        .kampus-badge span {
            background: #f1f5f9; color: #475569;
            border-radius: 20px; font-size: 11px; padding: 4px 12px; font-weight: 500;
        }
        .alert { border-radius: 8px; font-size: 13px; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-logo"><i class="bi bi-mortarboard-fill"></i></div>
    <h4>Sistem Penilaian</h4>
    <p class="sub">ITBM & STAIN Majene — Masuk untuk melanjutkan</p>

    @if($errors->any())
    <div class="alert alert-danger py-2">
        <i class="bi bi-exclamation-circle me-1"></i>
        {{ $errors->first() }}
    </div>
    @endif

    @if(session('status'))
    <div class="alert alert-info py-2">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login-post') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input type="email" id="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   placeholder="admin@penilaian.ac.id"
                   autocomplete="email" autofocus required>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label" for="password">Password</label>
            <div class="input-group">
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="••••••••"
                       autocomplete="current-password" required>
                <button class="btn btn-outline-secondary border" type="button" id="togglePwd">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label text-secondary small" for="remember">Ingat saya</label>
        </div>

        <button type="submit" class="btn-login">
            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
        </button>
    </form>

    <div class="kampus-badge">
        <span><i class="bi bi-building me-1"></i>ITBM</span>
        <span><i class="bi bi-building me-1"></i>STAIN Majene</span>
    </div>
</div>

<script>
    document.getElementById('togglePwd').addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });
</script>
</body>
</html>
