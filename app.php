<?php

/**
 * Configure routes
 */
$_SERVER["REQUEST_URI"] = str_replace("/_project_infopkb", "", $_SERVER["REQUEST_URI"]);

$app->get('/detail/{nopol}', function ($nopol) use ($app, $regional) {
    $nopol = preg_replace('/[^a-zA-Z0-9]/', '', $nopol);
    preg_match('/^([a-zA-Z]+)(\d+)([a-zA-Z]*)$/', $nopol, $matches);

    $result = [
        'KODE_WILAYAH' => strtoupper($matches[1] ?? ''),
        'NOMOR_POLISI' => strtoupper($matches[2] ?? ''),
        'KODE_PENDAFTARAN' => strtoupper($matches[3] ?? ''),
    ];

    if (empty($result['KODE_WILAYAH']) || empty($result['NOMOR_POLISI'])) {
        return $app->response
            ->setStatusCode(400)
            ->setJsonContent([
                'RC' => '0400',
                'RCM' => 'INVALID REQUEST PARAMETER',
            ])
            ->send();
    }

    $filteredRegional = array_filter($regional, function ($item) use ($result) {
        return in_array($result['KODE_WILAYAH'], $item['KODE_WILAYAH']);
    });

    $regionalData = !empty($filteredRegional) ? array_values($filteredRegional)[0] : null;

    if (empty($regionalData) || empty($_ENV[$regionalData['NAMA_PROVINSI']])) {
        return $app->response
            ->setStatusCode(501)
            ->setJsonContent([
                'RC' => '0501',
                'RCM' => 'NOT IMPLEMENTED',
            ])
            ->send();
    }

    $provinsiClass = 'App\\Library\\Provinsi\\' . $regionalData['NAMA_PROVINSI'];

    if (!class_exists($provinsiClass)) {
        return $app->response
            ->setStatusCode(502)
            ->setJsonContent([
                'RC' => '0502',
                'RCM' => 'BAD GATEWAY',
            ])
            ->send();
    }

    $detail = $provinsiClass::process($result);

    if (empty($detail)) {
        return $app->response
            ->setStatusCode(502)
            ->setJsonContent([
                'RC' => '0502',
                'RCM' => 'BAD GATEWAY',
            ])
            ->send();
    }

    return $app->response
        ->setStatusCode(200)
        ->setJsonContent([
            'RC' => '0200',
            'RCM' => 'SUCCESS',
            'DATA' => $detail,
        ])
        ->send();
});

$app->get('/regional', function () use ($app, $regional) {
    return $app->response
        ->setStatusCode(200)
        ->setJsonContent([
            'RC' => '0200',
            'RCM' => 'SUCCESS',
            'DATA' => $regional,
        ])
        ->send();
});

$app->get('/', function () use ($app) {
    return $app->response
        ->setStatusCode(200)
        ->setJsonContent([
            '/detail/{nopol}' => [
                'method' => 'GET',
                'parameters' => ['nopol' => 'B1234XYZ']
            ],
            '/regional' => [
                'method' => 'GET'
            ]
        ])
        ->send();
});

/*
 * 404 Not Found handler
 */
$app->notFound(function () use ($app) {
    return $app->response
        ->setStatusCode(404)
        ->setJsonContent([
            'RC' => '0404', 
            'RCM' => 'NOT FOUND'
        ])
        ->send();
});
