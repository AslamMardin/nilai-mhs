<?php namespace App\Http\Controllers;

use App\Models\Kampus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        $kampusList = Kampus::orderBy('nama')->get();
        return view('auth.login', compact('kampusList'));
    }

    public function login(Request $request)
    {
        // dd("asdas");
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }

        $request->session()->regenerate();

        // Simpan kampus pilihan saat login (jika dipilih)
        if ($request->filled('kampus_id')) {
            session(['kampus_id' => $request->kampus_id]);
            Auth::user()->update(['kampus_id' => $request->kampus_id]);
        }

        // Jika belum punya kampus → arahkan ke pilih kampus
        if (!session('kampus_id') && !Auth::user()->kampus_id) {
            return redirect()->route('pilih-kampus');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // Halaman pilih kampus setelah login (jika belum dipilih)
    public function showPilihKampus()
    {
        if (!Auth::check()) return redirect()->route('login');
        $kampusList = Kampus::withCount(['mahasiswa','mataKuliah','kelas'])->get();
        return view('auth.pilih-kampus', compact('kampusList'));
    }

    public function simpanPilihKampus(Request $request)
    {
        $request->validate(['kampus_id' => 'required|exists:kampus,id']);

        session(['kampus_id' => $request->kampus_id]);
        Auth::user()->update(['kampus_id' => $request->kampus_id]);

        return redirect()->route('dashboard')->with('success', 'Kampus berhasil dipilih.');
    }

    // Ganti kampus (dari navbar/dropdown) — bisa dilakukan kapan saja
    public function gantiKampus(Request $request)
    {
        $request->validate(['kampus_id' => 'required|exists:kampus,id']);
        session(['kampus_id' => $request->kampus_id]);
        Auth::user()->update(['kampus_id' => $request->kampus_id]);
        return back()->with('success', 'Kampus berhasil diganti.');
    }
}
