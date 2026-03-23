<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cegadat extends Model
{
    protected $table = 'cegadatok';

    protected $fillable = [
        'nev',
        'szekhelycim',
        'adoszam',
        'cegjegyzekszam',
        'telefon',
        'email',
        'bankszamlaszam',
        'web',
    ];

    /**
     * Returns the singleton company data row, creating a default one if missing.
     */
    public static function get(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'nev' => 'TRITON SECURITY KFT.',
                'szekhelycim' => '1234 Budapest, Minta utca 1.',
                'adoszam' => '12345678-2-42',
                'cegjegyzekszam' => '01-09-123456',
                'telefon' => '+36 1 234 5678',
                'email' => 'info@tritonsecurity.hu',
            ]
        );
    }
}
