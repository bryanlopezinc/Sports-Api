<?php

declare(strict_types=1);

return [
    'usernameMaxLength'     =>  15,
    'usernameMinLength'     =>  5,
    'displaynameMaxLength'  => 30,

    'tokens'    => [
        'accessTokenExpire'    => 90, // days
        'refreshTokenExpire'   => 90, // days
    ]
];