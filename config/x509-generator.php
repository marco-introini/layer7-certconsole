<?php

return [
    'cert_alg' => env('X509_CERT_ALG', 'sha512'),
    'cert_keybit' => intval(env('X509_KEYBIT', 4096)),
    'cert_country' => env('X509_CERT_COUNTRY', ''),
    'cert_state' => env('X509_CERT_STATE', ''),
    'cert_city' => env('X509_CERT_CITY', ''),
    'cert_organization_name' => env('X509_CERT_ORGANIZATION_NAME', ''),
    'cert_organization_unit' => env('X509_CERT_ORGANIZATION_UNIT', ''),
    'cert_email' => env('X509_CERT_EMAIL', ''),
];
