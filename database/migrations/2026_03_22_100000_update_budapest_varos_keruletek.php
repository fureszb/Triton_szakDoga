<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** Budapest irányítószámokhoz kerület neve: floor((irsz % 1000) / 10) → sorszám */
    private array $romanMap = [
        1  => 'I',   2  => 'II',   3  => 'III',  4  => 'IV',
        5  => 'V',   6  => 'VI',   7  => 'VII',  8  => 'VIII',
        9  => 'IX',  10 => 'X',    11 => 'XI',   12 => 'XII',
        13 => 'XIII', 14 => 'XIV', 15 => 'XV',   16 => 'XVI',
        17 => 'XVII', 18 => 'XVIII', 19 => 'XIX', 20 => 'XX',
        21 => 'XXI', 22 => 'XXII', 23 => 'XXIII',
    ];

    public function up(): void
    {
        // Csak azokat frissíti, ahol nev = 'Budapest' és az irszámból kerület azonosítható
        $rows = DB::table('varos')
            ->where('nev', 'Budapest')
            ->whereBetween('Irny_szam', [1011, 1239])
            ->get(['id', 'Irny_szam']);

        foreach ($rows as $row) {
            $kerSzam = (int) floor(($row->Irny_szam % 1000) / 10);
            if (! isset($this->romanMap[$kerSzam])) {
                continue;
            }
            DB::table('varos')->where('id', $row->id)->update([
                'nev'        => 'Budapest ' . $this->romanMap[$kerSzam] . '. kerület',
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Visszaállítja "Budapest"-re az összes kerület bejegyzést
        DB::table('varos')
            ->where('nev', 'LIKE', 'Budapest %. kerület')
            ->whereBetween('Irny_szam', [1011, 1239])
            ->update(['nev' => 'Budapest', 'updated_at' => now()]);
    }
};
