<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    protected $table = 'school_settings';

    protected $fillable = [
        'school_name',
        'school_code',
        'school_address',
        'school_phone',
        'school_email',
        'principal_name',
        'principal_nip',
        'counseling_head_name',
        'counseling_head_nip',
        'active_academic_year',
        'active_semester',
        'wa_gateway_token',
        'wa_gateway_endpoint',
        'logo_path',
    ];

    public static function getSettings(): self
    {
        return self::firstOrCreate([], [
            'school_name' => 'SMA / SMK NEGERI 1 TELADAN',
            'school_code' => 'NPSN-20261101',
            'school_address' => 'Jl. Pendidikan No. 100, Kota Nusantara',
            'school_phone' => '021-88997766',
            'school_email' => 'info@sekolah-teladan.sch.id',
            'principal_name' => 'Drs. H. Ahmad Fauzi, M.Pd.',
            'principal_nip' => '19700101 199512 1 001',
            'counseling_head_name' => 'Siti Nurhaliza, S.Pd., Kons.',
            'counseling_head_nip' => '19820510 200801 2 005',
            'active_academic_year' => '2026/2027',
            'active_semester' => 'Ganjil',
        ]);
    }
}
