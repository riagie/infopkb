<?php

namespace App\Library\Provinsi;

use App\Helpers\Curl;
use App\Helpers\ResponseValidator;
use DOMDocument;
use DOMXPath;

class KALIMANTAN_TIMUR
{
    public static function process(array $data): ?array
    {
        // Prepare request data
        $requestData = [
            'kt' => $data['KODE_WILAYAH'],
            'nomor' => $data['NOMOR_POLISI'],
            'seri' => $data['KODE_PENDAFTARAN'],
        ];

        // Send request and get response
        $response = Curl::request($_ENV['KALIMANTAN_TIMUR'], 'POST', $requestData);

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
            
            if (strpos($textParts[4], $data['NOMOR_POLISI']) == false) {
                return null;
            }
            
            // Map extracted data to response data
            $responseData = [
                'MERK'          => $textParts[8],
                'MODEL'         => $textParts[9],
                // 'JENIS'         => $textParts[],
                'TAHUN'         => (int) $textParts[10],
                // 'WARNA'         => $textParts[],
                // 'CC'            => (int) $textParts[],
                // 'BBM'           => $textParts[],
                // 'PLAT'          => $textParts[],
                'NO_POLISI'     => implode('', $data),
                'NO_RANGKA'     => $textParts[12],
                'NO_MESIN'      => $textParts[13],
                'NAMA_PEMILIK'  => $textParts[6],
                'ALAMAT'        => $textParts[7],

                'MILIK_KE'      => (int) $textParts[11],
                // 'WILAYAH'       => $textParts[],
                'TGL_PAJAK'     => preg_replace('/(\d{2})-(\d{2})-(\d{4})/', '$3$2$1', $textParts[14]),
                'TGL_STNK'      => preg_replace('/(\d{2})-(\d{2})-(\d{4})/', '$3$2$1', $textParts[15]),
                'PKB_POKOK'     => (int) str_replace(['.', ','], '', $textParts[16]),
                'PKB_DENDA'     => (int) str_replace(['.', ','], '', $textParts[17]),
                'SWDKLLJ_POKOK' => (int) str_replace(['.', ','], '', $textParts[18]),
                'SWDKLLJ_DENDA' => (int) str_replace(['.', ','], '', $textParts[19]),
                'PNPB_STNK'     => (int) str_replace(['.', ','], '', $textParts[20]),
                'PNPB_DENDA'    => (int) str_replace(['.', ','], '', $textParts[21]),
                'TOTAL'         => (int) str_replace(['.', ','], '', $textParts[22]),
            ];

            // Validate and return response data
            return ResponseValidator::validate($responseData);
        }

        return null;
    }
}
