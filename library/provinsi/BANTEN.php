<?php

namespace App\Library\Provinsi;

use App\Helpers\Curl;
use App\Helpers\ResponseValidator;
use DOMDocument;
use DOMXPath;

class BANTEN
{
    public static function process(array $data): ?array
    {
        // Prepare request data
        $requestData = [
            'kode' => $data['KODE_WILAYAH'],
            'nomor' => $data['NOMOR_POLISI'],
            'seri' => $data['KODE_PENDAFTARAN']
        ];

        // Send request and get response
        $response = Curl::request($_ENV['BANTEN'], 'POST', $requestData);

        if (strpos($response, 'DATA KENDARAAN TIDAK ADA!') == true) {
            return null;
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
                if (!empty($text)) {
                    $textParts[] = $text;
                }
            }
            
            // Map extracted data to response data
            $responseData = [
                'MERK'          => $textParts[18],
                'MODEL'         => $textParts[22],
                'JENIS'         => $textParts[26],
                'TAHUN'         => (int) (explode(' / ', $textParts[34])[0]),
                'WARNA'         => $textParts[38],
                'CC'            => (int) (explode(' / ', $textParts[34])[1]),
                'BBM'           => explode(' / ', $textParts[34])[2],
                'PLAT'          => $textParts[42],
                'NO_POLISI'     => implode('', $data),
                'NO_RANGKA'     => $textParts[30],
                'NO_MESIN'      => explode(' / ', $textParts[31])[1],
                'NAMA_PEMILIK'  => $textParts[12],
                'ALAMAT'        => $textParts[15],

                // 'MILIK_KE'      => (int) $textParts[],
                // 'WILAYAH'       => $textParts[],
                'TGL_PAJAK'     => preg_replace('/(\d{2})-(\d{2})-(\d{4})/', '$3$2$1', $textParts[47]),
                'TGL_STNK'      => preg_replace('/(\d{2})-(\d{2})-(\d{4})/', '$3$2$1', $textParts[51]),
                'PKB_POKOK'     => (int) preg_replace('/\D/', '', $textParts[59]),
                'PKB_DENDA'     => (int) preg_replace('/\D/', '', $textParts[63]),
                'SWDKLLJ_POKOK' => (int) preg_replace('/\D/', '', $textParts[67]),
                'SWDKLLJ_DENDA' => (int) preg_replace('/\D/', '', $textParts[71]),
                'PNPB_STNK'     => (int) preg_replace('/\D/', '', $textParts[75]),
                'PNPB_DENDA'    => (int) preg_replace('/\D/', '', $textParts[79]),
                'TOTAL'         => (int) preg_replace('/\D/', '', $textParts[87]),
            ];

            // Validate and return response data
            return ResponseValidator::validate($responseData);
        }

        return null;
    }
}
