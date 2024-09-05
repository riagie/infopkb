<?php

namespace App\Library\Provinsi;

use App\Helpers\Curl;
use App\Helpers\ResponseValidator;
use DOMDocument;
use DOMXPath;

class PAPUA
{
    public static function process(array $data, $number = 1): ?array
    {
        // Prepare request data
        $requestData = [
            'nopol' => $data['KODE_WILAYAH'] . $data['NOMOR_POLISI'] . $data['KODE_PENDAFTARAN'],
            'plat'  => "0" . $number,
            'pemutihan'  => "0"
        ];

        // Send request and get response
        $response = Curl::request($_ENV['PAPUA'], 'GET', $requestData);
        
        if (strpos($response, $requestData['nopol']) == false) {
            if ($number < 4) {
                return self::process($data, ($number + 1));
            }
        }
        
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
                'MERK'          => $textParts[49],
                'MODEL'         => $textParts[33],
                'JENIS'         => $textParts[41],
                'TAHUN'         => (int) $textParts[65],
                'WARNA'         => $textParts[57],
                // 'CC'            => (int) $textParts[],
                // 'BBM'           => $textParts[],
                // 'PLAT'          => $textParts[],
                'NO_POLISI'     => $requestData['nopol'],
                // 'NO_RANGKA'     => $textParts[],
                // 'NO_MESIN'      => $textParts[],
                // 'NAMA_PEMILIK'  => $textParts[],
                // 'ALAMAT'        => $textParts[],

                // 'MILIK_KE'      => (int) $textParts[],
                // 'WILAYAH'       => $textParts[],
                'TGL_PAJAK'     => preg_replace('/(\d{2})-(\d{2})-(\d{4})/', '$3$2$1', $textParts[75]),
                'TGL_STNK'      => preg_replace('/(\d{2})-(\d{2})-(\d{4})/', '$3$2$1', $textParts[87]),
                'PKB_POKOK'     => (int) str_replace(['.', ','], '', $textParts[125]),
                'PKB_DENDA'     => (int) str_replace(['.', ','], '', $textParts[127]),
                'SWDKLLJ_POKOK' => (int) str_replace(['.', ','], '', $textParts[143]),
                'SWDKLLJ_DENDA' => (int) str_replace(['.', ','], '', $textParts[145]),
                // 'PNPB_STNK'     => (int) str_replace(['.', ','], '', $textParts[]),
                // 'PNPB_DENDA'    => (int) str_replace(['.', ','], '', $textParts[]),
                'TOTAL'         => (int) str_replace(['.', ','], '', $textParts[165]),
            ];

            // Validate and return response data
            return ResponseValidator::validate($responseData);
        }

        return null;
    }
}
