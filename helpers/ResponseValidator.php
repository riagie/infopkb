<?php

namespace App\Helpers;

class ResponseValidator implements ValidatorInterface
{
    private static $requiredParameters = [
        'MERK',
        'MODEL',
        'JENIS',
        'TAHUN',
        'WARNA',
        'CC',
        'BBM',
        'PLAT',
        'NO_POLISI',
        'NO_RANGKA',
        'NO_MESIN',
        'NAMA_PEMILIK',
        'ALAMAT',

        'MILIK_KE',
        'WILAYAH',
        'TGL_PAJAK',
        'TGL_STNK',
        'PKB_POKOK',
        'PKB_DENDA',
        'SWDKLLJ_POKOK',
        'SWDKLLJ_DENDA',
        'PNPB_STNK',
        'PNPB_DENDA',
        'TOTAL',
    ];

    public static function validate(array $data): array
    {
        $validatedData = array_fill_keys(self::$requiredParameters, '');

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $validatedData)) {
                $validatedData[$key] = $value;
            }
        }

        return $validatedData;
    }
}
