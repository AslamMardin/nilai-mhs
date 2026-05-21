<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\Models\NilaiAkhir;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Paginator::useBootstrap();
        Blade::directive('active', function ($expression) {
            return "<?php echo request()->routeIs($expression) ? 'active' : ''; ?>";
        });

        // Global view composer for layout.app to provide $mahasiswaBerisiko
        View::composer('layouts.app', function ($view) {
            $kampusId = session('kampus_id') ?? Auth::user()?->kampus_id;
            if ($kampusId) {
                $mahasiswaBerisiko = NilaiAkhir::with(['mahasiswa.kelas', 'mataKuliah'])
                    ->whereHas('mahasiswa', fn($q) => $q->where('kampus_id', $kampusId))
                    ->where(function($q) {
                        $q->where('status_kelulusan', 'tidak_lulus')
                          ->orWhere('persentase_kehadiran', '<', 75.0)
                          ->orWhere('nilai_akhir', '<', 55);
                    })
                    ->latest()
                    ->take(10)
                    ->get();
                $view->with('mahasiswaBerisiko', $mahasiswaBerisiko);
            } else {
                $view->with('mahasiswaBerisiko', collect([]));
            }
        });
    }
}
