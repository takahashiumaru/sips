<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\TarifSpp;
use App\Models\Siswa;
use App\Models\TagihanSpp;
use App\Models\Pembayaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ──
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@sipspp.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        $bendahara = User::create([
            'name' => 'Sri Wahyuni',
            'email' => 'bendahara@sipspp.com',
            'password' => Hash::make('password'),
            'role' => 'bendahara',
            'phone' => '081234567891',
            'is_active' => true,
        ]);
        $bendahara->assignRole('bendahara');

        $kepsek = User::create([
            'name' => 'Dr. Hadi Susanto, M.Pd.',
            'email' => 'kepsek@sipspp.com',
            'password' => Hash::make('password'),
            'role' => 'kepala_sekolah',
            'phone' => '081234567892',
            'is_active' => true,
        ]);
        $kepsek->assignRole('kepala_sekolah');

        // Wali Murid
        $waliNames = [
            ['name' => 'Budi Santoso', 'email' => 'budi@gmail.com'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti@gmail.com'],
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@gmail.com'],
            ['name' => 'Dewi Sartika', 'email' => 'dewi@gmail.com'],
            ['name' => 'Joko Widodo', 'email' => 'joko@gmail.com'],
            ['name' => 'Rina Marlina', 'email' => 'rina@gmail.com'],
            ['name' => 'Hendra Wijaya', 'email' => 'hendra@gmail.com'],
            ['name' => 'Yuni Astuti', 'email' => 'yuni@gmail.com'],
            ['name' => 'Bambang Sutrisno', 'email' => 'bambang@gmail.com'],
            ['name' => 'Lestari Handayani', 'email' => 'lestari@gmail.com'],
        ];

        $walis = [];
        foreach ($waliNames as $w) {
            $wali = User::create([
                'name' => $w['name'],
                'email' => $w['email'],
                'password' => Hash::make('password'),
                'role' => 'wali_murid',
                'phone' => '08' . rand(1000000000, 9999999999),
                'is_active' => true,
            ]);
            $wali->assignRole('wali_murid');
            $walis[] = $wali;
        }

        // ── Tahun Ajaran ──
        $ta1 = TahunAjaran::create([
            'nama' => '2024/2025',
            'tahun_mulai' => 2024,
            'tahun_akhir' => 2025,
            'is_aktif' => true,
        ]);

        TahunAjaran::create([
            'nama' => '2025/2026',
            'tahun_mulai' => 2025,
            'tahun_akhir' => 2026,
            'is_aktif' => false,
        ]);

        // ── Kelas ──
        $kelasData = [
            ['nama_kelas' => '7A', 'tingkat' => 7],
            ['nama_kelas' => '7B', 'tingkat' => 7],
            ['nama_kelas' => '8A', 'tingkat' => 8],
            ['nama_kelas' => '8B', 'tingkat' => 8],
            ['nama_kelas' => '9A', 'tingkat' => 9],
            ['nama_kelas' => '9B', 'tingkat' => 9],
        ];

        $kelasModels = [];
        foreach ($kelasData as $k) {
            $kelasModels[] = Kelas::create([
                'tahun_ajaran_id' => $ta1->id,
                'nama_kelas' => $k['nama_kelas'],
                'tingkat' => $k['tingkat'],
                'wali_kelas' => 'Guru Kelas ' . $k['nama_kelas'],
            ]);
        }

        // ── Tarif SPP ──
        TarifSpp::create(['tahun_ajaran_id' => $ta1->id, 'tingkat' => 7, 'jumlah' => 150000, 'keterangan' => 'SPP Kelas 7', 'created_by' => $admin->id]);
        TarifSpp::create(['tahun_ajaran_id' => $ta1->id, 'tingkat' => 8, 'jumlah' => 150000, 'keterangan' => 'SPP Kelas 8', 'created_by' => $admin->id]);
        TarifSpp::create(['tahun_ajaran_id' => $ta1->id, 'tingkat' => 9, 'jumlah' => 175000, 'keterangan' => 'SPP Kelas 9', 'created_by' => $admin->id]);

        // ── Siswa ──
        $siswaData = [
            ['nis' => '20240001', 'nama' => 'Aisha Rahma', 'jk' => 'P', 'kelas' => 4, 'wali' => 0],
            ['nis' => '20240002', 'nama' => 'Budi Pratama', 'jk' => 'L', 'kelas' => 5, 'wali' => 1],
            ['nis' => '20240003', 'nama' => 'Citra Dewi', 'jk' => 'P', 'kelas' => 0, 'wali' => 2],
            ['nis' => '20240004', 'nama' => 'Dimas Prayoga', 'jk' => 'L', 'kelas' => 1, 'wali' => 3],
            ['nis' => '20240005', 'nama' => 'Eka Putri', 'jk' => 'P', 'kelas' => 2, 'wali' => 4],
            ['nis' => '20240006', 'nama' => 'Farhan Maulana', 'jk' => 'L', 'kelas' => 3, 'wali' => 5],
            ['nis' => '20240007', 'nama' => 'Gita Savitri', 'jk' => 'P', 'kelas' => 4, 'wali' => 6],
            ['nis' => '20240008', 'nama' => 'Haris Nugroho', 'jk' => 'L', 'kelas' => 5, 'wali' => 7],
            ['nis' => '20240009', 'nama' => 'Indah Permata', 'jk' => 'P', 'kelas' => 0, 'wali' => 8],
            ['nis' => '20240010', 'nama' => 'Jaka Tarub', 'jk' => 'L', 'kelas' => 1, 'wali' => 9],
            ['nis' => '20240011', 'nama' => 'Kartika Sari', 'jk' => 'P', 'kelas' => 2, 'wali' => 0],
            ['nis' => '20240012', 'nama' => 'Lukman Hakim', 'jk' => 'L', 'kelas' => 3, 'wali' => 1],
            ['nis' => '20240013', 'nama' => 'Maya Anggraeni', 'jk' => 'P', 'kelas' => 4, 'wali' => 2],
            ['nis' => '20240014', 'nama' => 'Naufal Rizky', 'jk' => 'L', 'kelas' => 5, 'wali' => 3],
            ['nis' => '20240015', 'nama' => 'Olivia Putri', 'jk' => 'P', 'kelas' => 0, 'wali' => 4],
            ['nis' => '20240016', 'nama' => 'Putra Wijaya', 'jk' => 'L', 'kelas' => 1, 'wali' => 5],
            ['nis' => '20240017', 'nama' => 'Qorina Zahra', 'jk' => 'P', 'kelas' => 2, 'wali' => 6],
            ['nis' => '20240018', 'nama' => 'Rizal Firmansyah', 'jk' => 'L', 'kelas' => 3, 'wali' => 7],
            ['nis' => '20240019', 'nama' => 'Sarah Amelia', 'jk' => 'P', 'kelas' => 4, 'wali' => 8],
            ['nis' => '20240020', 'nama' => 'Taufik Hidayat', 'jk' => 'L', 'kelas' => 5, 'wali' => 9],
        ];

        $siswaModels = [];
        foreach ($siswaData as $s) {
            $siswaModels[] = Siswa::create([
                'nis' => $s['nis'],
                'nama_lengkap' => $s['nama'],
                'jenis_kelamin' => $s['jk'],
                'tanggal_lahir' => fake()->dateTimeBetween('-15 years', '-12 years')->format('Y-m-d'),
                'alamat' => fake()->address(),
                'kelas_id' => $kelasModels[$s['kelas']]->id,
                'wali_murid_id' => $walis[$s['wali']]->id,
                'status' => 'aktif',
            ]);
        }

        // ── Tagihan SPP (Juli - Desember 2024) ──
        foreach ($siswaModels as $siswa) {
            $kelas = $kelasModels[array_search($siswa->kelas_id, array_column(
                array_map(fn($k) => ['id' => $k->id], $kelasModels), 'id'
            ))];
            $tarif = TarifSpp::where('tahun_ajaran_id', $ta1->id)
                ->where('tingkat', $kelas->tingkat)
                ->first();

            for ($bulan = 7; $bulan <= 12; $bulan++) {
                $status = 'belum_bayar';
                $totalDibayar = 0;

                // Randomize: some paid, some pending, some unpaid
                if ($bulan <= 10) {
                    $rand = rand(1, 10);
                    if ($rand <= 7) {
                        $status = 'lunas';
                        $totalDibayar = $tarif->jumlah;
                    } elseif ($rand <= 9) {
                        $status = 'menunggu_verifikasi';
                    }
                }

                $tagihan = TagihanSpp::create([
                    'siswa_id' => $siswa->id,
                    'tahun_ajaran_id' => $ta1->id,
                    'bulan' => $bulan,
                    'tahun' => 2024,
                    'jumlah_tagihan' => $tarif->jumlah,
                    'total_dibayar' => $totalDibayar,
                    'status' => $status,
                    'jatuh_tempo' => "2024-{$bulan}-10",
                    'created_by' => $admin->id,
                ]);

                // Create payment records for lunas
                if ($status === 'lunas') {
                    Pembayaran::create([
                        'tagihan_id' => $tagihan->id,
                        'jumlah_bayar' => $tarif->jumlah,
                        'tanggal_bayar' => "2024-{$bulan}-" . rand(1, 10) . ' ' . rand(8, 16) . ':' . rand(10, 59) . ':00',
                        'metode_bayar' => ['tunai', 'transfer', 'qris'][rand(0, 2)],
                        'status_verifikasi' => 'terverifikasi',
                        'dicatat_oleh' => $bendahara->id,
                        'diverifikasi_oleh' => $bendahara->id,
                        'diverifikasi_at' => now(),
                    ]);
                }
            }
        }
    }
}
