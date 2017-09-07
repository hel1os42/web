<?php


return [
    \Illuminate\Http\Response::HTTP_UNAUTHORIZED => '401 Unauthorized',
    \Illuminate\Http\Response::HTTP_FORBIDDEN => '403 Access denied',
    \Illuminate\Http\Response::HTTP_NOT_FOUND => '404 Page not found',
    'invalid_email_or_password' => 'Invalid email or password.',
    'offer_not_found' => 'The offer you are looking for, does not exist, or it is on moderation',
    'transaction_not_found' => 'You don\'t have permission to access this transaction.',
];
