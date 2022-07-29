<?php

return [
    'jwt_expires_in_day' => env('JWT_EXPIRES_IN_DAY', 3),
    'jwt_private_key_path' => env('JWT_PRIVATE_KEY_PATH', 'private.pem'),
    'jwt_public_key_path' => env('JWT_PUBLIC_KEY_PATH', 'public.pem'),
    'jwt_pass_phrase' => env('JWT_PASS_PHRASE', ''),
];
