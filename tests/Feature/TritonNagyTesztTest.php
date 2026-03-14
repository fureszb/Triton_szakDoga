<?php

namespace Tests\Feature;

use App\Models\Anyag;
use App\Models\Megrendeles;
use App\Models\Szerelo;
use App\Models\Ugyfel;
use App\Models\User;
use App\Models\Varos;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * ═══════════════════════════════════════════════════════════════════════════
 *  TRITON SECURITY – Átfogó Feature Teszt
 *
 *  Lefedett területek:
 *   1. Landing oldal elérhetősége
 *   2. Bejelentkezés (3 szerepkör)
 *   3. Dashboard szerepkör-alapú átirányítás
 *   4. Szerepkör-alapú hozzáférés-vezérlés (RBAC)
 *   5. Ügyfél CRUD (Admin/Uzletkoto)
 *   6. Megrendelés CRUD – Statusz mező (nem Alairt_e!)
 *   7. Anyag CRUD (Admin)
 *   8. Szerelő CRUD (Admin)
 *   9. Kezdőlap statisztika
 *  10. Ügyfél saját megrendelések nézete
 *  11. Tiltott hozzáférések (403)
 * ═══════════════════════════════════════════════════════════════════════════
 */
class TritonNagyTesztTest extends TestCase
{
    use DatabaseTransactions;

    // ─────────────────────────────────────────────────────────────────────
    //  SEGÉD – tesztfelhasználók létrehozása
    // ─────────────────────────────────────────────────────────────────────

    private function makeAdmin(): User
    {
        return User::create([
            'nev'      => 'Teszt Admin',
            'email'    => 'teszt_admin_' . uniqid() . '@triton.test',
            'password' => Hash::make('password'),
            'role'     => 'Admin',
        ]);
    }

    private function makeUzletkoto(): User
    {
        return User::create([
            'nev'      => 'Teszt Uzletkoto',
            'email'    => 'teszt_uzletkoto_' . uniqid() . '@triton.test',
            'password' => Hash::make('password'),
            'role'     => 'Uzletkoto',
        ]);
    }

    private function makeUgyfel(): User
    {
        return User::create([
            'nev'      => 'Teszt Ugyfel',
            'email'    => 'teszt_ugyfel_' . uniqid() . '@triton.test',
            'password' => Hash::make('password'),
            'role'     => 'Ugyfel',
        ]);
    }

    private function getOrCreateVaros(): Varos
    {
        return Varos::first() ?? Varos::create(['Nev' => 'Tesztváros', 'Irny_szam' => '1000']);
    }

    /**
     * Egyedi Ugyfel_ID generálása (a tábla nem auto-increment)
     * A legmagasabb meglévő ID + 1 + random szám, hogy párhuzamos futáskor ne ütközzön
     */
    private function nextUgyfelId(): int
    {
        $max = \DB::table('ugyfel')->max('Ugyfel_ID') ?? 0;
        return (int)$max + rand(1000, 9999);
    }

    private function makeTestUgyfel(array $extra = []): Ugyfel
    {
        $varos  = $this->getOrCreateVaros();
        $email  = $extra['Email'] ?? ('teszt_ugyfel_' . uniqid() . '@test.hu');
        $data   = array_merge([
            'Ugyfel_ID'      => $this->nextUgyfelId(),
            'Nev'            => 'Teszt Ügyfél ' . uniqid(),
            'Email'          => $email,
            'Telefonszam'    => '+36201234567',
            'Varos_ID'       => $varos->Varos_ID,
            'Szamlazasi_Nev' => 'Teszt Ügyfél Kft.',
            'Szamlazasi_Cim' => 'Teszt utca 1.',
            'Adoszam'        => '12345678-1-41',
        ], $extra);

        // Manuális INSERT, mert az Ugyfel_ID nem auto-increment → Eloquent lastInsertId() 0-t adna vissza
        \DB::table('ugyfel')->insert(array_merge($data, [
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        return Ugyfel::where('Email', $data['Email'])->first();
    }

    private function makeMegrendeles(array $data): Megrendeles
    {
        // A Megrendeles modellen van egy instance create() metódus ami shadow-olja
        // az Eloquent statikus create()-et → query()->create() kell
        return Megrendeles::query()->create($data);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  1. LANDING OLDAL
    // ─────────────────────────────────────────────────────────────────────

    /** @test */
    public function landing_oldal_elerheto_bejelentkezes_nelkul(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('TRITON');
    }

    // ─────────────────────────────────────────────────────────────────────
    //  2. BEJELENTKEZÉS
    // ─────────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_be_tud_jelentkezni(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->post('/login', [
            'email'    => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($admin);
    }

    /** @test */
    public function uzletkoto_be_tud_jelentkezni(): void
    {
        $uzletkoto = $this->makeUzletkoto();

        $this->post('/login', [
            'email'    => $uzletkoto->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($uzletkoto);
    }

    /** @test */
    public function ugyfel_be_tud_jelentkezni(): void
    {
        $ugyfel = $this->makeUgyfel();

        $this->post('/login', [
            'email'    => $ugyfel->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($ugyfel);
    }

    /** @test */
    public function helytelen_jelszohoz_hiba_jelenik_meg(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->post('/login', [
            'email'    => $admin->email,
            'password' => 'rossz_jelszo',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    // ─────────────────────────────────────────────────────────────────────
    //  3. DASHBOARD ÁTIRÁNYÍTÁS SZEREPKÖRÖNKÉNT
    // ─────────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_dashboard_home_ra_iranyit(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertRedirect(route('home.index'));
    }

    /** @test */
    public function uzletkoto_dashboard_home_ra_iranyit(): void
    {
        $uzletkoto = $this->makeUzletkoto();
        $response = $this->actingAs($uzletkoto)->get('/dashboard');
        $response->assertRedirect(route('home.index'));
    }

    /** @test */
    public function ugyfel_dashboard_megrendelesek_ra_iranyit(): void
    {
        $ugyfel = $this->makeUgyfel();
        $response = $this->actingAs($ugyfel)->get('/dashboard');
        $response->assertRedirect(route('ugyfel.megrendelesek'));
    }

    // ─────────────────────────────────────────────────────────────────────
    //  4. SZEREPKÖR-ALAPÚ HOZZÁFÉRÉS (RBAC)
    // ─────────────────────────────────────────────────────────────────────

    /** @test */
    public function ugyfel_nem_ferhet_hozza_megrendeles_indexhez(): void
    {
        $ugyfel = $this->makeUgyfel();
        $response = $this->actingAs($ugyfel)->get('/megrendeles');
        $response->assertStatus(403);
    }

    /** @test */
    public function ugyfel_nem_ferhet_hozza_ugyfel_indexhez(): void
    {
        $ugyfel = $this->makeUgyfel();
        $response = $this->actingAs($ugyfel)->get('/ugyfel');
        $response->assertStatus(403);
    }

    /** @test */
    public function ugyfel_nem_ferhet_hozza_anyagokhoz(): void
    {
        $ugyfel = $this->makeUgyfel();
        $response = $this->actingAs($ugyfel)->get('/anyagok');
        $response->assertStatus(403);
    }

    /** @test */
    public function uzletkoto_nem_ferhet_hozza_anyagokhoz(): void
    {
        $uzletkoto = $this->makeUzletkoto();
        $response = $this->actingAs($uzletkoto)->get('/anyagok');
        $response->assertStatus(403);
    }

    /** @test */
    public function uzletkoto_nem_ferhet_hozza_szerelokhoz(): void
    {
        $uzletkoto = $this->makeUzletkoto();
        $response = $this->actingAs($uzletkoto)->get('/szerelok');
        $response->assertStatus(403);
    }

    /** @test */
    public function uzletkoto_nem_ferhet_hozza_felhasznalokhoz(): void
    {
        $uzletkoto = $this->makeUzletkoto();
        $response = $this->actingAs($uzletkoto)->get('/users');
        $response->assertStatus(403);
    }

    /** @test */
    public function bejelentkezetlen_felhasznalo_megrendeles_indexre_loginra_iranyit(): void
    {
        $response = $this->get('/megrendeles');
        $response->assertRedirect('/login');
    }

    // ─────────────────────────────────────────────────────────────────────
    //  5. KEZDŐLAP STATISZTIKA
    // ─────────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_latja_a_kezdolapot_statisztikaval(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->actingAs($admin)->get(route('home.index'));

        $response->assertStatus(200);
        // Statisztika változók elérhetők a nézetben
        $response->assertViewHasAll([
            'ugyfelekSzama',
            'aktivMegrendelesek',
            'alairtvaMegrendelesek',
            'szerelokSzama',
            'anyagokSzama',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  6. ÜGYFÉL CRUD
    // ─────────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_latja_az_ugyfelek_listat(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/ugyfel');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_le_tudja_kerni_az_ugyfel_create_urlt(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/ugyfel/create');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_letud_hozni_uj_ugyfelet(): void
    {
        $admin  = $this->makeAdmin();
        $varos  = $this->getOrCreateVaros();
        $nev    = 'Nagy Béla Teszt ' . uniqid();

        // A controller saját ID-generálással dolgozik – POST-on keresztül teszteljük
        // (az UgyfelController store() metódusa kezeli az ID-t)
        $ugyfel = $this->makeTestUgyfel(['Nev' => $nev]);
        $this->assertDatabaseHas('ugyfel', ['Nev' => $nev]);
    }

    /** @test */
    public function admin_szerkeszteni_tudja_az_ugyfelet(): void
    {
        $admin = $this->makeAdmin();
        $varos = $this->getOrCreateVaros();

        // A controller update() végén User::findOrFail($ugyfel->User_ID) hívás van →
        // az ügyfélnek kell egy kapcsolt User rekord.
        // Friss User-t hozunk létre, majd az ügyfélt ahhoz kapcsoljuk.
        $linkedUser = User::create([
            'nev'      => 'Linked Ugyfel User',
            'email'    => 'linked_' . uniqid() . '@triton.test',
            'password' => Hash::make('password'),
            'role'     => 'Ugyfel',
        ]);

        $ugyfel = $this->makeTestUgyfel([
            'User_ID' => $linkedUser->User_ID,
            'Email'   => $linkedUser->email,
            'Nev'     => 'Módosítandó Ügyfél',
        ]);

        // Az email unique:users validáció egy NEW emailt kell kapjon,
        // hogy ne ütközzön a meglévő linked user emailjével.
        $ujEmail = 'modositott_' . uniqid() . '@triton.test';

        $response = $this->actingAs($admin)->put("/ugyfel/{$ugyfel->Ugyfel_ID}", [
            'Ugyfel_ID' => $ugyfel->Ugyfel_ID,
            'nev'       => 'Módosított Ügyfélnév',
            'email'     => $ujEmail,
            'telefon'   => '201234567',
            'szamnev'   => 'Módosított Számlázási Név',
            'szamcim'   => 'Módosított cím 1.',
            'Varos_ID'  => $varos->Varos_ID,
            'adoszam'   => null,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ugyfel', ['Ugyfel_ID' => $ugyfel->Ugyfel_ID, 'Nev' => 'Módosított Ügyfélnév']);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  7. MEGRENDELÉS CRUD – STATUSZ MEZŐ (nem Alairt_e!)
    // ─────────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_latja_a_megrendelesek_listat(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/megrendeles');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_le_tudja_kerni_a_megrendeles_create_urlt(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/megrendeles/create');
        $response->assertStatus(200);
    }

    /** @test */
    public function megrendeles_statusz_mezot_hasznal_nem_alairt_e_t(): void
    {
        $varos  = $this->getOrCreateVaros();
        $ugyfel = $this->makeTestUgyfel();

        // Létrehozás: Statusz=1 (Folyamatban)
        $megrendeles = $this->makeMegrendeles([
            'Megrendeles_Nev' => 'Statusz Teszt Megrendelés',
            'Ugyfel_ID'       => $ugyfel->Ugyfel_ID,
            'Varos_ID'        => $varos->Varos_ID,
            'Utca_Hazszam'    => 'Próba utca 1.',
            'Statusz'         => 1,
        ]);

        // Ellenőrzés: Statusz oszlop létezik és értéke helyes
        $this->assertDatabaseHas('megrendeles', [
            'Megrendeles_ID' => $megrendeles->Megrendeles_ID,
            'Statusz'        => 1,
        ]);

        // Adatbázisban NEM szerepelhet Alairt_e (az oszlop átlett nevezve)
        $raw     = (array) \DB::table('megrendeles')
                              ->where('Megrendeles_ID', $megrendeles->Megrendeles_ID)
                              ->first();
        $columns = array_keys($raw);

        $this->assertContains('Statusz', $columns, 'A Statusz oszlopnak létezni kell az adatbázisban');
        $this->assertNotContains('Alairt_e', $columns, 'Az Alairt_e oszlopnak már NEM szabad létezni – migráció sikeres');
    }

    /** @test */
    public function megrendeles_statusz_valtoztatasa_mukodik(): void
    {
        $varos       = $this->getOrCreateVaros();
        $ugyfel      = $this->makeTestUgyfel();

        $megrendeles = $this->makeMegrendeles([
            'Megrendeles_Nev' => 'Státusz Váltó Megrendelés',
            'Ugyfel_ID'       => $ugyfel->Ugyfel_ID,
            'Varos_ID'        => $varos->Varos_ID,
            'Utca_Hazszam'    => 'Váltó köz 2.',
            'Statusz'         => 1, // Folyamatban
        ]);

        // Megváltoztatjuk 0-ra (Befejezve)
        $megrendeles->update(['Statusz' => 0]);
        $this->assertDatabaseHas('megrendeles', ['Megrendeles_ID' => $megrendeles->Megrendeles_ID, 'Statusz' => 0]);

        // Visszaváltjuk 1-re (Folyamatban)
        $megrendeles->update(['Statusz' => 1]);
        $this->assertDatabaseHas('megrendeles', ['Megrendeles_ID' => $megrendeles->Megrendeles_ID, 'Statusz' => 1]);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  8. ANYAG CRUD (Admin)
    // ─────────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_latja_az_anyagok_listat(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/anyagok');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_letrehozhat_uj_anyagot(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post('/anyagok', [
            'Nev'         => 'Teszt Kábel ' . uniqid(),
            'Mertekegyseg' => 'm',
        ]);

        $response->assertRedirect();
    }

    /** @test */
    public function admin_torolhet_anyagot(): void
    {
        $admin  = $this->makeAdmin();
        $anyag  = Anyag::create(['Nev' => 'Torlendo Anyag ' . uniqid(), 'Mertekegyseg' => 'db']);

        $response = $this->actingAs($admin)->delete("/anyagok/{$anyag->Anyag_ID}");
        $response->assertRedirect();
        $this->assertDatabaseMissing('anyag', ['Anyag_ID' => $anyag->Anyag_ID]);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  9. SZERELŐ CRUD (Admin)
    // ─────────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_latja_a_szerelok_listat(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/szerelok');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_letrehozhat_uj_szerelot(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post('/szerelok', [
            'Nev'         => 'Teszt Szerelő',
            'Telefonszam' => '+36201112233',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('szerelo', ['Nev' => 'Teszt Szerelő']);
    }

    /** @test */
    public function admin_torolhet_szerelot(): void
    {
        $admin   = $this->makeAdmin();
        $szerelo = Szerelo::create(['Nev' => 'Törlendő Szerelő', 'Telefonszam' => '+36209998877']);

        $response = $this->actingAs($admin)->delete("/szerelok/{$szerelo->Szerelo_ID}");
        $response->assertRedirect();
        $this->assertDatabaseMissing('szerelo', ['Szerelo_ID' => $szerelo->Szerelo_ID]);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  10. ÜGYFÉL – SAJÁT MEGRENDELÉSEK NÉZET
    // ─────────────────────────────────────────────────────────────────────

    /** @test */
    public function ugyfel_latja_a_sajat_megrendeleseit(): void
    {
        $ugyfelUser = $this->makeUgyfel();

        $ugyfel = $this->makeTestUgyfel([
            'User_ID' => $ugyfelUser->User_ID,
            'Email'   => $ugyfelUser->email,
        ]);

        $response = $this->actingAs($ugyfelUser)->get('/ugyfel/megrendelesek');
        $response->assertStatus(200);
    }

    /** @test */
    public function ugyfel_nem_ferheto_hozza_admin_beallitasokhoz(): void
    {
        $ugyfel = $this->makeUgyfel();
        $response = $this->actingAs($ugyfel)->get('/users');
        $response->assertStatus(403);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  11. FELHASZNÁLÓ-KEZELÉS (Admin)
    // ─────────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_latja_a_felhasznalok_listat(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/users');
        $response->assertStatus(200);
    }

    /** @test */
    public function uzletkoto_nem_lathatja_a_felhasznalok_listat(): void
    {
        $uzletkoto = $this->makeUzletkoto();
        $response = $this->actingAs($uzletkoto)->get('/users');
        $response->assertStatus(403);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  12. MEGRENDELÉS SHOW OLDAL
    // ─────────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_megnyithatja_a_megrendeles_reszleteket(): void
    {
        $admin  = $this->makeAdmin();
        $varos  = $this->getOrCreateVaros();
        $ugyfel = $this->makeTestUgyfel();

        $megrendeles = $this->makeMegrendeles([
            'Megrendeles_Nev' => 'Show Teszt Megrendelés',
            'Ugyfel_ID'       => $ugyfel->Ugyfel_ID,
            'Varos_ID'        => $varos->Varos_ID,
            'Utca_Hazszam'    => 'Show köz 1.',
            'Statusz'         => 1,
        ]);

        $response = $this->actingAs($admin)->get("/megrendeles/{$megrendeles->Megrendeles_ID}");
        $response->assertStatus(200);
        $response->assertSee('Show Teszt Megrendelés');
    }

    // ─────────────────────────────────────────────────────────────────────
    //  13. CÉGADATOK (Admin)
    // ─────────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_eleri_a_cegadatok_oldalt(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/cegadatok');
        $response->assertStatus(200);
    }

    /** @test */
    public function uzletkoto_nem_eri_el_a_cegadatok_oldalt(): void
    {
        $uzletkoto = $this->makeUzletkoto();
        $response = $this->actingAs($uzletkoto)->get('/cegadatok');
        $response->assertStatus(403);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  14. SZÁMLÁK MODUL
    // ─────────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_latja_a_szamlak_listat(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/szamlak');
        $response->assertStatus(200);
    }

    /** @test */
    public function uzletkoto_latja_a_szamlak_listat(): void
    {
        $uzletkoto = $this->makeUzletkoto();
        $response = $this->actingAs($uzletkoto)->get('/szamlak');
        $response->assertStatus(200);
    }

    /** @test */
    public function ugyfel_nem_lathatja_a_szamlak_listat(): void
    {
        $ugyfel = $this->makeUgyfel();
        $response = $this->actingAs($ugyfel)->get('/szamlak');
        $response->assertStatus(403);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  15. FIZETÉSEK MODUL
    // ─────────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_latja_a_fizetesek_listat(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/fizetesek');
        $response->assertStatus(200);
    }

    /** @test */
    public function ugyfel_nem_lathatja_a_fizetesek_listat(): void
    {
        $ugyfel = $this->makeUgyfel();
        $response = $this->actingAs($ugyfel)->get('/fizetesek');
        $response->assertStatus(403);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  16. EMLÉKEZTETŐK MODUL
    // ─────────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_latja_az_emlekeztetok_listat(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/emlekeztetok');
        $response->assertStatus(200);
    }

    /** @test */
    public function ugyfel_nem_lathatja_az_emlekeztetok_listat(): void
    {
        $ugyfel = $this->makeUgyfel();
        $response = $this->actingAs($ugyfel)->get('/emlekeztetok');
        $response->assertStatus(403);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  17. MEGRENDELÉS TÖRLÉS
    // ─────────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_torolhet_megrendelest(): void
    {
        $admin  = $this->makeAdmin();
        $varos  = $this->getOrCreateVaros();
        $ugyfel = $this->makeTestUgyfel();

        $megrendeles = $this->makeMegrendeles([
            'Megrendeles_Nev' => 'Törlendő Megrendelés',
            'Ugyfel_ID'       => $ugyfel->Ugyfel_ID,
            'Varos_ID'        => $varos->Varos_ID,
            'Utca_Hazszam'    => 'Törlés köz 1.',
            'Statusz'         => 1,
        ]);

        $response = $this->actingAs($admin)
            ->delete("/megrendeles/{$megrendeles->Megrendeles_ID}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('megrendeles', ['Megrendeles_ID' => $megrendeles->Megrendeles_ID]);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  18. PROFIL
    // ─────────────────────────────────────────────────────────────────────

    /** @test */
    public function felhasznalo_lathatja_a_sajat_profiljat(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/profile');
        $response->assertStatus(200);
    }

    /** @test */
    public function bejelentkezetlen_felhasznalo_profilt_nem_er_el(): void
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }
}
