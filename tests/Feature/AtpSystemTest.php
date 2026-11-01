<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\LokasiPresensi;
use App\Models\PolaJamKerja;
use App\Models\TarifPajakTer;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AtpSystemTest extends TestCase
{
    use DatabaseTransactions;

    protected User $asnUser;
    protected Pegawai $pegawai;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::where('username', 'admin')->first();
        $this->asnUser = User::where('username', '198501152010011012')->first();
        $this->pegawai = Pegawai::where('nip', '198501152010011012')->first();
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Satu Portal Terpadu ASN');
    }

    public function test_asn_can_login_with_nip(): void
    {
        $response = $this->post('/login', [
            'username' => '198501152010011012',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('portal.dashboard'));
        $this->assertAuthenticatedAs($this->asnUser);
    }

    public function test_authenticated_user_can_view_portal_dashboard(): void
    {
        $response = $this->actingAs($this->asnUser)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Ahmad Fauzi');
        $response->assertSee('Presensi ASN');
        $response->assertSee('Manajemen TPP');
        $response->assertSee('Pajak TER PP 58/2023');
    }

    public function test_multi_domain_routing_absensi_local(): void
    {
        $response = $this->actingAs($this->asnUser)->get('http://absensi.local/');

        $response->assertStatus(200);
        $response->assertSee('Presensi GPS Mandiri');
    }

    public function test_multi_domain_routing_tpp_local(): void
    {
        $response = $this->actingAs($this->asnUser)->get('http://tpp.local/');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Kinerja');
    }

    public function test_multi_domain_routing_pajakasn_local(): void
    {
        $response = $this->actingAs($this->asnUser)->get('http://pajakasn.local/');

        $response->assertStatus(200);
        $response->assertSee('Pajak TER PP 58/2023');
    }

    public function test_dynamic_work_schedule_admin_panel(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/jam-kerja');
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Jam Kerja Dinamis');
        $response->assertSee('Pola 5 Hari Kerja (Reguler Kantor)');
        $response->assertSee('id="admin-sidebar"', false);
        $response->assertSee('id="mobile-sidebar-backdrop"', false);
    }

    public function test_admin_dashboard_and_sidebar(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Pengelola ATP');
        $response->assertSee('id="admin-sidebar"', false);
        $response->assertSee('id="mobile-sidebar-backdrop"', false);
        $response->assertSee('Jadwal & Jam Kerja', false);
        $response->assertSee('Lokasi & Geofence', false);
    }

    public function test_sidebar_navigation_rendered_for_asn_role_across_modules(): void
    {
        $routes = [
            '/' => 'Portal Terpadu',
            '/absensi' => 'Presensi Mandiri GPS',
            '/tpp' => 'Dashboard Kinerja & Realisasi TPP',
            '/pajak' => 'Pajak TER PP 58/2023',
            '/absensi/riwayat' => 'Riwayat Kehadiran',
            '/tpp/aktivitas' => 'Log Aktivitas Harian',
            '/pajak/simulator' => 'Simulator TER',
        ];

        foreach ($routes as $route => $expectedText) {
            $response = $this->actingAs($this->asnUser)->get($route);
            $response->assertStatus(200);
            $response->assertSee('id="admin-sidebar"', false);
            $response->assertSee('id="mobile-sidebar-backdrop"', false);
            $response->assertSee('openAdminSidebar()', false);
            $response->assertSee($expectedText, false);
        }
    }

    public function test_sidebar_navigation_rendered_for_admin_role_across_modules(): void
    {
        $adminRoutes = [
            '/' => 'Portal Terpadu',
            '/admin' => 'Dashboard Pengelola ATP',
            '/admin/lokasi' => 'Titik Koordinat',
            '/admin/pegawai' => 'Master Pegawai',
            '/absensi' => 'Presensi Mandiri GPS',
            '/tpp' => 'Dashboard Kinerja & Realisasi TPP',
            '/pajak' => 'Pajak TER PP 58/2023',
            '/jam-kerja' => 'Pengaturan Jam Kerja Dinamis',
        ];

        foreach ($adminRoutes as $route => $expectedText) {
            $response = $this->actingAs($this->adminUser)->get($route);
            $response->assertStatus(200);
            $response->assertSee('id="admin-sidebar"', false);
            $response->assertSee('id="mobile-sidebar-backdrop"', false);
            $response->assertSee('openAdminSidebar()', false);
            $response->assertSee($expectedText, false);
        }
    }

    public function test_presensi_checkin_within_geofence_succeeds(): void
    {
        $lokasi = LokasiPresensi::first();

        // Exact coordinates of BKPSDM
        $response = $this->actingAs($this->asnUser)->post('/absensi/check-in', [
            'latitude' => $lokasi->latitude,
            'longitude' => $lokasi->longitude,
            'lokasi_id' => $lokasi->id,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('presensi', [
            'pegawai_id' => $this->pegawai->id,
            'lokasi_masuk_id' => $lokasi->id,
        ]);
    }

    public function test_presensi_checkin_outside_geofence_is_rejected(): void
    {
        $lokasi = LokasiPresensi::first();

        // Coordinates 50 kilometers away
        $response = $this->actingAs($this->asnUser)->post('/absensi/check-in', [
            'latitude' => -3.999999,
            'longitude' => 114.999999,
            'lokasi_id' => $lokasi->id,
        ]);

        $response->assertSessionHas('error');
    }

    public function test_tax_ter_calculation_for_categories(): void
    {
        // 5.000.000 in TER_A should be 0%
        $tarifA = TarifPajakTer::getTarif('TER_A', 5000000);
        $this->assertEquals(0.00, $tarifA);

        // 8.000.000 in TER_A should be 1.5%
        $tarifA2 = TarifPajakTer::getTarif('TER_A', 8000000);
        $this->assertEquals(1.50, $tarifA2);

        // 8.000.000 in TER_B should be 1.0%
        $tarifB = TarifPajakTer::getTarif('TER_B', 8000000);
        $this->assertEquals(1.00, $tarifB);
    }
}
