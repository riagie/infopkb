<?php

$provinsiList = [
    "DKI_JAKARTA",
    // "BANTEN",
    "JAWA_TENGAH",
    "JAWA_TIMUR",
    "DAERAH_ISTIMEWA_YOGYAKARTA",
    "KALIMANTAN_UTARA",
    "KALIMANTAN_TIMUR",
    "KALIMANTAN_TENGAH",
    "KALIMANTAN_BARAT",
    "KALIMANTAN_SELATAN",
    "SUMATRA_BARAT",
    "BENGKULU",
    "SUMATRA_UTARA",
    "LAMPUNG",
    "SUMATRA_SELATAN",
    "JAMBI",
    "SUMATRA_UTARA_TIMUR",
    "ACEH",
    "RIAU",
    "BANGKA_BELITUNG",
    "KEPULAUAN_RIAU",
    "JAWA_BARAT",
    "SULAWESI_BARAT",
    "SULAWESI_SELATAN",
    "SULAWESI_TENGAH",
    "SULAWESI_TENGGARA",
    "GORONTALO",
    "BOLAANG_MONGONDOW",
    "BALI",
    "NUSA_TENGGARA_BARAT",
    "NUSA_TENGGARA_TIMUR",
    "ROTE_NDAO",
    "LOMBOK",
    "MALUKU",
    "MALUKU_UTARA",
    "PAPUA",
    "PAPUA_BARAT",
];

$baseDirectory = 'D:\\_project\\1.initial-Pre\\_project_infopkb\\library\\provinsi\\';

$template = <<<EOT
<?php

namespace App\Library\Provinsi;

use App\Helpers\Curl;
use App\Helpers\ResponseValidator;

class {{PROVINSI}}
{
    public static function process(array \$data): ?array
    {
        // Prepare request data

        // Send request and get response
        \$response = Curl::request(\$_ENV['{{PROVINSI}}'], 'POST', \$requestData);

        // Check if response is valid
        if (\$response && !is_null(\$response) && \$response !== false) {

            // Map extracted data to response data
            // \$responseData = [
            //     'MERK'          => \$textParts[],
            //     'MODEL'         => \$textParts[],
            //     'JENIS'         => \$textParts[],
            //     'TAHUN'         => (int) \$textParts[],
            //     'WARNA'         => \$textParts[],
            //     'CC'            => (int) \$textParts[],
            //     'BBM'           => \$textParts[],
            //     'PLAT'          => \$textParts[],
            //     'NO_POLISI'     => implode('', \$requestData),
            //     'NO_RANGKA'     => \$textParts[],
            //     'NO_MESIN'      => \$textParts[],
            //     'NAMA_PEMILIK'  => \$textParts[],
            //     'ALAMAT'        => \$textParts[],

            //     'MILIK_KE'      => (int) \$textParts[],
            //     'WILAYAH'       => \$textParts[],
            //     'TGL_PAJAK'     => preg_replace('/(\d{2})-(\d{2})-(\d{4})/', '$3$2$1', \$textParts[]),
            //     'TGL_STNK'      => preg_replace('/(\d{2})-(\d{2})-(\d{4})/', '$3$2$1', \$textParts[]),
            //     'PKB_POKOK'     => (int) \$textParts[],
            //     'PKB_DENDA'     => (int) \$textParts[],
            //     'SWDKLLJ_POKOK' => (int) \$textParts[],
            //     'SWDKLLJ_DENDA' => (int) \$textParts[],
            //     'PNPB_STNK'     => (int) \$textParts[],
            //     'PNPB_DENDA'    => (int) \$textParts[],
            //     'TOTAL'         => (int) \$textParts[],
            // ];

            // Validate and return response data
            // return ResponseValidator::validate(\$responseData);
        }

        return null;
    }
}

EOT;

foreach ($provinsiList as $provinsi) {
    $classContent = str_replace('{{PROVINSI}}', $provinsi, $template);
    $fileName = $baseDirectory . $provinsi . '.php';

    if (!file_exists($baseDirectory)) {
        mkdir($baseDirectory, 0777, true);
    }

    file_put_contents($fileName, $classContent);
}

echo "Files have been created successfully.\n";

