<?php

namespace App\Library\Provinsi;

use App\Helpers\Curl;
use App\Helpers\ResponseValidator;
use DOMDocument;
use DOMXPath;

class DAERAH_ISTIMEWA_YOGYAKARTA
{
    public static function process(array $data): ?array
    {
        // Prepare request data
        $requestData = [
            'nomer' => $data['NOMOR_POLISI'],
            'kode_belakang' => $data['KODE_PENDAFTARAN']
        ];

        // Send request and get response
        $response = Curl::request($_ENV['DAERAH_ISTIMEWA_YOGYAKARTA'], 'POST', $requestData);

        // Check if response is valid
        if ($response && !is_null($response) && $response !== false) {
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($response);
            libxml_clear_errors();

            $xpath = new DOMXPath($dom);
            $nodes = $xpath->query('//body//text()');

            // Extract text parts from response
            $textParts = [];
            foreach ($nodes as $node) {
                $text = trim($node->nodeValue);
                $textParts[] = $text;
            }
            
            // Map extracted data to response data
            $responseData = [
                'MERK'          => $textParts[14],
                'MODEL'         => $textParts[20],
                // 'JENIS'         => $textParts[],
                'TAHUN'         => (int) $textParts[26],
                // 'WARNA'         => $textParts[],
                // 'CC'            => (int) $textParts[],
                // 'BBM'           => $textParts[],
                // 'PLAT'          => $textParts[],
                'NO_POLISI'     => implode('', $requestData),
                // 'NO_RANGKA'     => $textParts[],
                // 'NO_MESIN'      => $textParts[],
                // 'NAMA_PEMILIK'  => $textParts[],
                // 'ALAMAT'        => $textParts[],

                // 'MILIK_KE'      => (int) $textParts[],
                // 'WILAYAH'       => $textParts[],
                'TGL_PAJAK'     => preg_replace('/(\d{2})-(\d{2})-(\d{4})/', '$3$2$1', $textParts[74]),
                // 'TGL_STNK'      => preg_replace('/(\d{2})-(\d{2})-(\d{4})/', '$3$2$1', $textParts[]),
                'PKB_POKOK'     => (int) str_replace(['.', ','], '', $textParts[32]),
                'PKB_DENDA'     => (int) str_replace(['.', ','], '', $textParts[38]),
                'SWDKLLJ_POKOK' => (int) str_replace(['.', ','], '', $textParts[50]),
                'SWDKLLJ_DENDA' => (int) str_replace(['.', ','], '', $textParts[56]),
                // 'PNPB_STNK'     => (int) str_replace(['.', ','], '', $textParts[]),
                // 'PNPB_DENDA'    => (int) str_replace(['.', ','], '', $textParts[]),
                'TOTAL'         => (int) str_replace(['.', ','], '', $textParts[68]),
            ];

            // Validate and return response data
            return ResponseValidator::validate($responseData);
        }

        return null;
    }
}
