<?php

namespace App\Library\Provinsi;

use App\Helpers\Curl;
use App\Helpers\ResponseValidator;
use DOMDocument;
use DOMXPath;

class SUMATRA_SELATAN
{
    public static function process(array $data): ?array
    {
        // Prepare request data
        $requestData = [
            'nopol2' => $data['NOMOR_POLISI'],
            'nopol3' => $data['KODE_PENDAFTARAN'],
        ];

        // Send request and get response
        $response = Curl::request($_ENV['SUMATRA_SELATAN'], 'POST', $requestData);

        if (strpos($response, $data['KODE_WILAYAH'] . $data['NOMOR_POLISI'] . $data['KODE_PENDAFTARAN']) == false) {
            return null;
        }
        
        // Check if response is valid
        if ($response && !is_null($response) && $response !== false) {
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($response);
            libxml_clear_errors();

            $xpath = new DOMXPath($dom);
            $nodes = $xpath->query('//table//input');

            // Extract text parts from response
            $textParts = [];
            foreach ($nodes as $node) {
                $text = trim($node->getAttribute('value'));
                $textParts[] = $text;
            }

            // Map extracted data to response data
            $responseData = [
                'MERK'          => $textParts[22],
                'MODEL'         => $textParts[23],
                'JENIS'         => $textParts[21],
                'TAHUN'         => (int) $textParts[24],
                'WARNA'         => $textParts[28],
                'CC'            => (int) $textParts[26],
                'BBM'           => $textParts[25],
                'PLAT'          => $textParts[27],
                'NO_POLISI'     => implode('', $data),
                // 'NO_RANGKA'     => $textParts[],
                // 'NO_MESIN'      => $textParts[],
                // 'NAMA_PEMILIK'  => $textParts[],
                // 'ALAMAT'        => $textParts[],

                'MILIK_KE'      => (int) $textParts[20],
                // 'WILAYAH'       => $textParts[],
                'TGL_PAJAK'     => preg_replace('/(\d{2})-(\d{2})-(\d{4})/', '$3$2$1', $textParts[29]),
                'TGL_STNK'      => preg_replace('/(\d{2})-(\d{2})-(\d{4})/', '$3$2$1', $textParts[30]),
                'PKB_POKOK'     => (int) str_replace(['.', ','], '', $textParts[5]),
                'PKB_DENDA'     => (int) str_replace(['.', ','], '', $textParts[6]),
                'SWDKLLJ_POKOK' => (int) str_replace(['.', ','], '', $textParts[8]),
                'SWDKLLJ_DENDA' => (int) str_replace(['.', ','], '', $textParts[9]),
                'PNPB_STNK'     => (int) str_replace(['.', ','], '', $textParts[13]),
                'PNPB_DENDA'    => (int) str_replace(['.', ','], '', $textParts[14]),
                'TOTAL'         => (int) str_replace(['.', ','], '', $textParts[19]),
            ];

            // Validate and return response data
            return ResponseValidator::validate($responseData);
        }

        return null;
    }
}
