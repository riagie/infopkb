<?php

namespace App\Library\Provinsi;

use App\Helpers\Curl;
use App\Helpers\ResponseValidator;
use DOMDocument;
use DOMXPath;

class JAMBI
{
    public static function process(array $data): ?array
    {
        // Prepare request data
        $requestData = [
            'no_polisi' => $data['KODE_WILAYAH'] . $data['NOMOR_POLISI'] . $data['KODE_PENDAFTARAN'],
            'nm_pemilik' => '',
            'tg_akhir_pkb' => '',
            'tg_akhir_stnk' => '',
        ];

        // Send request and get response
        $response = Curl::request($_ENV['JAMBI'], 'POST', $requestData);

        if (strpos($response, $data['KODE_WILAYAH'] .' '. $data['NOMOR_POLISI'] .' '. $data['KODE_PENDAFTARAN']) == false) {
            return null;
        }
        
        // Check if response is valid
        if ($response && !is_null($response) && $response !== false) {
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($response);
            libxml_clear_errors();

            $xpath = new DOMXPath($dom);
            $nodes = $xpath->query('//table//td');

            // Extract text parts from response
            $textParts = [];
            foreach ($nodes as $node) {
                $text = trim($node->nodeValue);
                $textParts[] = $text;
            }
            
            // Map extracted data to response data
            $responseData = [
                'MERK'          => $textParts[2],
                'MODEL'         => $textParts[5],
                'JENIS'         => $textParts[8],
                'TAHUN'         => (int) $textParts[11],
                'WARNA'         => $textParts[17],
                'CC'            => (int) $textParts[14],
                // 'BBM'           => $textParts[],
                // 'PLAT'          => $textParts[],
                'NO_POLISI'     => implode('', $data),
                // 'NO_RANGKA'     => $textParts[],
                // 'NO_MESIN'      => $textParts[],
                // 'NAMA_PEMILIK'  => $textParts[],
                // 'ALAMAT'        => $textParts[],

                // 'MILIK_KE'      => (int) $textParts[],
                'WILAYAH'       => $textParts[23],
                'TGL_PAJAK'     => preg_replace('/(\d{2})\/(\d{2})\/(\d{4})/', '$3$2$1', $textParts[26]),
                'TGL_STNK'      => preg_replace('/(\d{2})\/(\d{2})\/(\d{4})/', '$3$2$1', $textParts[29]),
                'PKB_POKOK'     => (int) preg_replace('/\D/', '', $textParts[35]),
                'PKB_DENDA'     => (int) preg_replace('/\D/', '', $textParts[56]),
                'SWDKLLJ_POKOK' => (int) preg_replace('/\D/', '', $textParts[38]),
                'SWDKLLJ_DENDA' => (int) preg_replace('/\D/', '', $textParts[59]),
                'PNPB_STNK'     => (int) preg_replace('/\D/', '', $textParts[41]),
                'PNPB_DENDA'    => (int) preg_replace('/\D/', '', $textParts[44]),
                'TOTAL'         => (int) preg_replace('/\D/', '', $textParts[50]),
            ];

            // Validate and return response data
            return ResponseValidator::validate($responseData);
        }

        return null;
    }
}
