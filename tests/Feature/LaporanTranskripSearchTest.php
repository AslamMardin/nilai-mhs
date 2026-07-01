<?php

namespace Tests\Feature;

use App\Models\Kampus;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanTranskripSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_transkrip_can_be_searched_by_name(): void
    {
        $user = User::factory()->create();
        $kampus = Kampus::create([
            'nama' => 'Universitas Testing',
            'kode' => 'UT01',
        ]);
        $kelas = Kelas::create([
            'kampus_id' => $kampus->id,
            'nama' => 'TI 1',
            'kode' => 'TI1',
        ]);
        $mahasiswa = Mahasiswa::create([
            'kampus_id' => $kampus->id,
            'kelas_id' => $kelas->id,
            'nim' => '20240001',
            'nama' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'status' => 'aktif',
        ]);

        $this->actingAs($user)
            ->get('/laporan/transkrip?query=' . urlencode('Budi'))
            ->assertOk()
            ->assertViewHas('mahasiswa', function ($found) use ($mahasiswa) {
                return $found && $found->id === $mahasiswa->id;
            });
    }
}
