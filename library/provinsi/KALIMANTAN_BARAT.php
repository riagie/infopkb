<?php

namespace App\Library\Provinsi;

use App\Helpers\Curl;
use App\Helpers\ResponseValidator;

class KALIMANTAN_BARAT
{
    public static function process(array $data): ?array
    {
        // Prepare request data
        $requestData = [
            'ac' => 'proses-check',
            'no_hp' => '81234567890',
            'inputkb' => $data['KODE_WILAYAH'] . $data['NOMOR_POLISI'] . $data['KODE_PENDAFTARAN'],
            '_' => time() . 000,
        ];

        $requestHeader = [
            'Host: bapenda.kalbarprov.go.id',
            'X-Requested-With: XMLHttpRequest',
        ];
        
        // Send request and get response
        $response = Curl::request($_ENV['KALIMANTAN_BARAT'], 'GET', $requestData, $requestHeader);

        // Check if response is valid
        if ($response && !is_null($response) && $response !== false) {
            $response = json_decode($response, true);

            if ($response['status'] == false || $response['data'][0]['jenis_kbm'] == false) {
                return null;
            }
            // Map extracted data to response data
            $responseData = [
                'MERK'          => $response['data'][0]['merk'],
                // 'MODEL'         => $response['data'][0][''],
                'JENIS'         => $response['data'][0]['jenis_kbm'],
                'TAHUN'         => (int) $response['data'][0]['tahun_rakit'],
                // 'WARNA'         => $response['data'][0][''],
                'CC'            => (int) $response['data'][0]['besar_cc'],
                'BBM'           => $response['data'][0]['bahan_bakar'],
                'PLAT'          => $response['data'][0]['plat'],
                'NO_POLISI'     => implode('', $data),
                // 'NO_RANGKA'     => $response['data'][0][''],
                // 'NO_MESIN'      => $response['data'][0][''],
                // 'NAMA_PEMILIK'  => $response['data'][0][''],
                // 'ALAMAT'        => $response['data'][0][''],

                // 'MILIK_KE'      => (int) $response['data'][0][''],
                // 'WILAYAH'       => $response['data'][0][''],
                // 'TGL_PAJAK'     => preg_replace('/(\d{2})-(\d{2})-(\d{4})/', '$3$2$1', $response['data'][0]['']),
                // 'TGL_STNK'      => preg_replace('/(\d{2})-(\d{2})-(\d{4})/', '$3$2$1', $response['data'][0]['']),
                'PKB_POKOK'     => (int) str_replace(['.', ','], '', $response['data'][0]['pkb_pokok']),
                'PKB_DENDA'     => (int) str_replace(['.', ','], '', $response['data'][0]['pkb_denda']),
                'SWDKLLJ_POKOK' => (int) str_replace(['.', ','], '', $response['data'][0]['swddkllj_pokok']),
                'SWDKLLJ_DENDA' => (int) str_replace(['.', ','], '', $response['data'][0]['swddkllj_dendda']),
                // 'PNPB_STNK'     => (int) str_replace(['.', ','], '', $response['data'][0]['']),
                // 'PNPB_DENDA'    => (int) str_replace(['.', ','], '', $response['data'][0]['']),
                'TOTAL'         => (int) str_replace(['.', ','], '', $response['data'][0]['grand_total']),
            ];

            // Validate and return response data
            return ResponseValidator::validate($responseData);
        }

        return null;
    }
}
