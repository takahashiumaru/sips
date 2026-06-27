<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\TagihanSpp;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PaymentReportingTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_dashboard_payment_trend_uses_verified_payment_date(): void
    {
        Carbon::setTestNow('2026-02-15 10:00:00');
        $admin = $this->seedBillingFixture();
        $januaryBill = TagihanSpp::firstWhere(['bulan' => 1, 'tahun' => 2026]);

        Pembayaran::create([
            'tagihan_id' => $januaryBill->id,
            'jumlah_bayar' => 250000,
            'tanggal_bayar' => '2026-02-05 09:00:00',
            'metode_bayar' => 'tunai',
            'status_verifikasi' => 'terverifikasi',
            'dicatat_oleh' => $admin->id,
            'diverifikasi_oleh' => $admin->id,
            'diverifikasi_at' => '2026-02-05 09:00:00',
        ]);

        Pembayaran::create([
            'tagihan_id' => $januaryBill->id,
            'jumlah_bayar' => 999999,
            'tanggal_bayar' => '2026-02-06 09:00:00',
            'metode_bayar' => 'transfer',
            'status_verifikasi' => 'pending',
            'dicatat_oleh' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk();
        $trend = $response->viewData('trenData');

        $this->assertSame(0, $trend[0]);
        $this->assertSame(250000.0, $trend[1]);
    }

    public function test_monthly_recap_uses_only_verified_payments_for_bill_period(): void
    {
        Carbon::setTestNow('2026-02-15 10:00:00');
        $admin = $this->seedBillingFixture();
        $januaryBill = TagihanSpp::firstWhere(['bulan' => 1, 'tahun' => 2026]);
        $februaryBill = TagihanSpp::firstWhere(['bulan' => 2, 'tahun' => 2026]);

        Pembayaran::create([
            'tagihan_id' => $januaryBill->id,
            'jumlah_bayar' => 250000,
            'tanggal_bayar' => '2026-02-05 09:00:00',
            'metode_bayar' => 'tunai',
            'status_verifikasi' => 'terverifikasi',
            'dicatat_oleh' => $admin->id,
            'diverifikasi_oleh' => $admin->id,
            'diverifikasi_at' => '2026-02-05 09:00:00',
        ]);

        Pembayaran::create([
            'tagihan_id' => $februaryBill->id,
            'jumlah_bayar' => 250000,
            'tanggal_bayar' => '2026-02-06 09:00:00',
            'metode_bayar' => 'transfer',
            'status_verifikasi' => 'pending',
            'dicatat_oleh' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get(route('laporan.rekap', ['tahun' => 2026]));

        $response->assertOk();
        $recap = $response->viewData('rekap');

        $this->assertSame(250000.0, $recap[0]['total_tagihan']);
        $this->assertSame(250000.0, $recap[0]['total_bayar']);
        $this->assertSame(1, $recap[0]['lunas']);
        $this->assertSame(100.0, $recap[0]['persentase']);

        $this->assertSame(250000.0, $recap[1]['total_tagihan']);
        $this->assertSame(0.0, $recap[1]['total_bayar']);
        $this->assertSame(0, $recap[1]['lunas']);
        $this->assertSame(0.0, $recap[1]['persentase']);
    }

    private function seedBillingFixture(): User
    {
        $admin = User::create([
            'name' => 'Admin Keuangan',
            'email' => 'admin@example.test',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $tahunAjaran = TahunAjaran::create([
            'nama' => '2025/2026',
            'tahun_mulai' => 2025,
            'tahun_akhir' => 2026,
            'is_aktif' => true,
        ]);

        $kelas = Kelas::create([
            'tahun_ajaran_id' => $tahunAjaran->id,
            'nama_kelas' => '1A',
            'tingkat' => 1,
        ]);

        $siswa = Siswa::create([
            'nis' => '20260001',
            'nama_lengkap' => 'Alya Prameswari',
            'jenis_kelamin' => 'P',
            'kelas_id' => $kelas->id,
            'status' => 'aktif',
        ]);

        foreach ([1, 2] as $month) {
            TagihanSpp::create([
                'siswa_id' => $siswa->id,
                'tahun_ajaran_id' => $tahunAjaran->id,
                'bulan' => $month,
                'tahun' => 2026,
                'jumlah_tagihan' => 250000,
                'total_dibayar' => 0,
                'status' => 'belum_bayar',
                'jatuh_tempo' => sprintf('2026-%02d-10', $month),
                'created_by' => $admin->id,
            ]);
        }

        return $admin;
    }
}
