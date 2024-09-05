<?php

namespace App\Library\Provinsi;

use App\Helpers\Curl;
use App\Helpers\ResponseValidator;

class LAMPUNG
{
    public static function process(array $data): ?array
    {
        // Prepare request data

        // Send request and get response
        $response = Curl::request($_ENV['LAMPUNG'], 'POST', $requestData);

        // Check if response is valid
        if ($response && !is_null($response) && $response !== false) {

            // Map extracted data to response data
            // $responseData = [
            //     'MERK'          => $textParts[],
            //     'MODEL'         => $textParts[],
            //     'JENIS'         => $textParts[],
            //     'TAHUN'         => (int) $textParts[],
            //     'WARNA'         => $textParts[],
            //     'CC'            => (int) $textParts[],
            //     'BBM'           => $textParts[],
            //     'PLAT'          => $textParts[],
            //     'NO_POLISI'     => implode('', $requestData),
            //     'NO_RANGKA'     => $textParts[],
            //     'NO_MESIN'      => $textParts[],
            //     'NAMA_PEMILIK'  => $textParts[],
            //     'ALAMAT'        => $textParts[],

            //     'MILIK_KE'      => (int) $textParts[],
            //     'WILAYAH'       => $textParts[],
            //     'TGL_PAJAK'     => preg_replace('/(\d{2})-(\d{2})-(\d{4})/', '$3$2$1', $textParts[]),
            //     'TGL_STNK'      => preg_replace('/(\d{2})-(\d{2})-(\d{4})/', '$3$2$1', $textParts[]),
            //     'PKB_POKOK'     => (int) str_replace(['.', ','], '', $textParts[]),
            //     'PKB_DENDA'     => (int) str_replace(['.', ','], '', $textParts[]),
            //     'SWDKLLJ_POKOK' => (int) str_replace(['.', ','], '', $textParts[]),
            //     'SWDKLLJ_DENDA' => (int) str_replace(['.', ','], '', $textParts[]),
            //     'PNPB_STNK'     => (int) str_replace(['.', ','], '', $textParts[]),
            //     'PNPB_DENDA'    => (int) str_replace(['.', ','], '', $textParts[]),
            //     'TOTAL'         => (int) str_replace(['.', ','], '', $textParts[]),
            // ];

            // Validate and return response data
            // return ResponseValidator::validate($responseData);
        }

        return null;
    }
}
