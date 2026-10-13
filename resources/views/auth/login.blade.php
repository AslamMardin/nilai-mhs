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

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1px solid #dfe7f1;
            padding: 0.75rem 0.9rem;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #1a4a7a;
            box-shadow: 0 0 0 0.2rem rgba(26, 74, 122, 0.15);
        }

        .input-group-text {
            background: #f8fafc;
            border-color: #dfe7f1;
            color: #1a4a7a;
        }

        .btn-login {
            background: linear-gradient(135deg, #1a4a7a, #2563eb);
            border: none;
            border-radius: 10px;
            padding: 0.8rem 1rem;
            font-weight: 600;
            letter-spacing: 0.2px;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 8px 18px rgba(26, 74, 122, 0.22);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #143962, #1d4f9d);
            transform: translateY(-1px);
            box-shadow: 0 12px 22px rgba(26, 74, 122, 0.28);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 6px 14px rgba(26, 74, 122, 0.2);
        }

        .kampus-list {
            display: grid;
            gap: 0.65rem;
            max-height: 220px;
            overflow-y: auto;
            padding: 2px;
        }

        .kampus-option {
            position: relative;
            display: block;
            cursor: pointer;
        }

        .kampus-option input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
        }

        .kampus-card {
            min-height: 58px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border: 1px solid #dfe7f1;
            border-radius: 10px;
            transition: border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
        }

        .kampus-option:hover .kampus-card {
            border-color: #9bb9d7;
            background: #f8fbff;
        }

        .kampus-option input:checked + .kampus-card {
            border-color: #1a4a7a;
            background: #eff6ff;
            box-shadow: 0 0 0 2px rgba(26, 74, 122, 0.1);
        }

        .kampus-option input:focus-visible + .kampus-card {
            outline: 3px solid rgba(37, 99, 235, 0.35);
            outline-offset: 2px;
        }

        .kampus-icon {
            width: 36px;
            height: 36px;
            flex: 0 0 36px;
            display: grid;
            place-items: center;
            border-radius: 9px;
            background: #f1f5f9;
            color: #1a4a7a;
        }

        .kampus-option input:checked + .kampus-card .kampus-icon {
            background: #dbeafe;
        }

        .kampus-info {
            min-width: 0;
            display: grid;
        }

        .kampus-info strong {
            overflow: hidden;
            color: #1e293b;
            font-size: 0.9rem;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .kampus-info small {
            color: #64748b;
            font-size: 0.75rem;
        }

        .kampus-check {
            margin-left: auto;
            color: #1a4a7a;
            opacity: 0;
        }

        .kampus-option input:checked + .kampus-card .kampus-check {
            opacity: 1;
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

                <div class="mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="form-label mb-0" id="kampus-label">Pilih Kampus</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="kampus-search-toggle"
                            aria-label="Cari kampus" aria-expanded="false" aria-controls="kampus-search">
                            <i class="bi bi-search" aria-hidden="true"></i>
                        </button>
                    </div>
                    <input type="search" id="kampus-search" class="form-control mb-2 d-none"
                        placeholder="Cari kode atau nama kampus..." autocomplete="off">

                    <div class="kampus-list" role="radiogroup" aria-labelledby="kampus-label">
                        @foreach ($kampusList as $k)
                            <label class="kampus-option">
                                <input type="radio" name="kampus_id" value="{{ $k->id }}" required
                                    @checked(old('kampus_id') == $k->id)>
                                <span class="kampus-card">
                                    <span class="kampus-icon">
                                        <i class="bi bi-building"></i>
                                    </span>
                                    <span class="kampus-info">
                                        <strong>{{ $k->nama }}</strong>
                                        <small>{{ $k->kode }}</small>
                                    </span>
                                    <i class="bi bi-check-circle-fill kampus-check" aria-hidden="true"></i>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <p class="small text-muted mt-2 mb-0 d-none" id="kampus-empty">Kampus tidak ditemukan.</p>
                </div>

               

                <button class="btn btn-login w-100 text-white">
                    Masuk
                </button>

            </form>
        </div>
    </div>

    <script>
        const kampusSearch = document.getElementById('kampus-search');
        const kampusOptions = document.querySelectorAll('.kampus-option');
        const kampusEmpty = document.getElementById('kampus-empty');
        const kampusSearchToggle = document.getElementById('kampus-search-toggle');

        kampusSearch.addEventListener('input', function () {
            const keyword = this.value.trim().toLocaleLowerCase('id');
            let visibleCount = 0;

            kampusOptions.forEach(function (option) {
                const isVisible = option.textContent.toLocaleLowerCase('id').includes(keyword);
                option.hidden = !isVisible;
                visibleCount += isVisible ? 1 : 0;
            });

            kampusEmpty.classList.toggle('d-none', visibleCount > 0);
        });

        kampusSearchToggle.addEventListener('click', function () {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            const icon = this.querySelector('i');

            this.setAttribute('aria-expanded', String(!isExpanded));
            kampusSearch.classList.toggle('d-none', isExpanded);
            icon.className = isExpanded ? 'bi bi-search' : 'bi bi-x-lg';
            this.setAttribute('aria-label', isExpanded ? 'Cari kampus' : 'Tutup pencarian kampus');

            if (isExpanded) {
                kampusSearch.value = '';
                kampusSearch.dispatchEvent(new Event('input'));
            } else {
                kampusSearch.focus();
            }
        });
    </script>
</body>

</html>
