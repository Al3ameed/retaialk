<?php

if (config('app.env') === 'production') {
    return [
        /* Bosta for production */
        'bosta_url' => 'https://api.bosta.co/api/v0/',
        'bosta_token' => 'd9c594d797f5544462897552e00f1c871ba9f8ef3e02b702519d645177a0e120',
        'webhook_url' => 'https://khotwh.retailak.com/api/shipment/webhook',
        'weaccept_secret_key' => 'CE55EFDCD99AE8EB860CF5D495D0AD2C',
    ];
} else {
    return [
        /* Bosta For Staging */
        'bosta_url' => 'https://staging-api.bosta.co/api/v0/',
        'bosta_token' => 'dda8754586cddeb4fa8484560b481b9eff2d1f2de7be7669462f010357f085d1',
        'webhook_url' => 'http://163.172.8.204/khotwh_backend/api/shipment/webhook',
        'weaccept_secret_key' => 'CE55EFDCD99AE8EB860CF5D495D0AD2C',

    ];
}
