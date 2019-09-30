<?php

return [
    [
        'client_id' => 1,
        'token' => 'token-correct',
        'expired_at' => time() + 3600,
    ],
    [
        'client_id' => 1,
        'token' => 'token-expired',
        'expired_at' => time() - 3600,
    ],
];