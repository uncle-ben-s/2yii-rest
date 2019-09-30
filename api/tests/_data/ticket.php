<?php

$currentTimeStamp = time();

$expired = $currentTimeStamp + (3600*24*7);

return [
    [
        'id' => 1,
        'column' => 1,
        'row' => 1,
        'amount' => 55,
        'status' => "open",
        'block_expired_at' => null,
        'expired_at' => $expired
    ],
    [
        'id' => 2,
        'column' => 2,
        'row' => 1,
        'amount' => 55,
        'status' => "open",
        'block_expired_at' => null,
        'expired_at' => $expired
    ],
    [
        'id' => 3,
        'column' => 3,
        'row' => 1,
        'amount' => 55,
        'status' => "close",
        'block_expired_at' => $currentTimeStamp - 3600,
        'expired_at' => $expired
    ],
    [
        'id' => 4,
        'column' => 4,
        'row' => 1,
        'amount' => 55,
        'status' => "open",
        'block_expired_at' => null,
        'expired_at' => $currentTimeStamp - 3600
    ],
    [
        'id' => 5,
        'column' => 5,
        'row' => 1,
        'amount' => 55,
        'status' => "open",
        'block_expired_at' => $currentTimeStamp + 300,//5min
        'expired_at' => $expired
    ],
    [
        'id' => 6,
        'column' => 6,
        'row' => 1,
        'amount' => 55,
        'status' => "reserve",
        'block_expired_at' => $currentTimeStamp - 3600,
        'expired_at' => $currentTimeStamp + 900//15 min
    ],
    [
        'id' => 7,
        'column' => 7,
        'row' => 1,
        'amount' => 55,
        'status' => "reserve",
        'block_expired_at' => $currentTimeStamp - 3600,
        'expired_at' => $expired
    ],
];