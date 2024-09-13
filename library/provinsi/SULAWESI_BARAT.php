<?php

namespace App\Library\Provinsi;

use App\Helpers\Curl;
use App\Helpers\ResponseValidator;

class SULAWESI_BARAT
{
    public static function process(array $data): ?array
    {
        // Prepare request data
        $requestData = [
            'no_polisi' => $data['KODE_WILAYAH'] . $data['NOMOR_POLISI'] . $data['KODE_PENDAFTARAN']
        ];
        
        // Send request and get response
        $response = Curl::request($_ENV['SULAWESI_BARAT'], 'POST', $requestData);

        // Check if response is valid
        if ($response && !is_null($response) && $response !== false) {
            $response = json_decode($response, true);
            
            if (!$response) {
                return null;
            }
            
            // Map extracted data to response data
            $responseData = [
                'MERK'          => $response[0]['koding']['merek'],
                'MODEL'         => $response[0]['koding']['model'],
                'JENIS'         => $response[0]['jeniskb']['nama_jenis_kendaraan'],
                'TAHUN'         => (int) $response[0]['th_buatan'],
                'WARNA'         => $response[0]['warna_kb'],
                'CC'            => (int) $response[0]['jumlah_cc'],
                // 'BBM'           => $response[0][''],
                // 'PLAT'          => $response[0][''],
                'NO_POLISI'     => implode('', $data),
                'NO_RANGKA'     => $response[0]['no_chasis'],
                'NO_MESIN'      => $response[0]['no_mesin'],
                'NAMA_PEMILIK'  => $response[0]['nm_pemilik'],
                'ALAMAT'        => $response[0]['al_pemilik'],

                'MILIK_KE'      => (int) $response[0]['kd_jen_mohon'],
                'WILAYAH'       => $response[0]['kode_samsat'],
                'TGL_PAJAK'     => preg_replace('/(\d{2})-(\d{2})-(\d{4})/', '$3$2$1', $response[0]['tg_akhir_pkb']),
                'TGL_STNK'      => preg_replace('/(\d{2})-(\d{2})-(\d{4})/', '$3$2$1', $response[0]['tg_akhir_stnk']),
                'PKB_POKOK'     => (int) str_replace(['.', ','], '', $response[0]['bea_pkb_pok']),
                'PKB_DENDA'     => (int) str_replace(['.', ','], '', $response[0]['tunggakan']['den_pkb']),
                'SWDKLLJ_POKOK' => (int) str_replace(['.', ','], '', $response[0]['bea_swdkllj_pok']),
                'SWDKLLJ_DENDA' => (int) str_replace(['.', ','], '', $response[0]['tunggakan']['den_swd']),
                // 'PNPB_STNK'     => (int) str_replace(['.', ','], '', $response[0]['']),
                // 'PNPB_DENDA'    => (int) str_replace(['.', ','], '', $response[0]['']),
                'TOTAL'         => (int) str_replace(['.', ','], '', (
                    (int) str_replace(['.', ','], '', $response[0]['bea_pkb_pok']) + 
                    (int) str_replace(['.', ','], '', $response[0]['tunggakan']['den_pkb']) + 
                    (int) str_replace(['.', ','], '', $response[0]['bea_swdkllj_pok']) + 
                    (int) str_replace(['.', ','], '', $response[0]['tunggakan']['den_swd'])
                )),
            ];

            // Validate and return response data
            return ResponseValidator::validate($responseData);
        }

        return null;
    }
}
