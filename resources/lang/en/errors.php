<?php


return [
    \Illuminate\Http\Response::HTTP_BAD_REQUEST           => '400 Bad request',
    \Illuminate\Http\Response::HTTP_UNAUTHORIZED          => '401 Unauthorized',
    \Illuminate\Http\Response::HTTP_FORBIDDEN             => '403 Access denied',
    \Illuminate\Http\Response::HTTP_NOT_FOUND             => '404 Page not found',
    \Illuminate\Http\Response::HTTP_METHOD_NOT_ALLOWED    => '405 Method not allowed',
    \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR => '500 Ooops... something went wrong.',
    \Illuminate\Http\Response::HTTP_SERVICE_UNAVAILABLE   => '503 Service unavailable. Please try again some later.',
    'invalid_email_or_password'                           => 'Invalid email or password.',
    'invalid_code'                                        => 'Wrong OTP code.',
    'offer_not_found'                                     => 'The offer you are looking for, does not exist, or it is on moderation',
    'transaction_not_found'                               => 'You don\'t have permission to access this transaction.',
    'offer_unprocessable_entity'                          => 'Forbidden to delete offer with status \'active\'.',
    'operator_not_found'                                  => 'The operator you are looking for, does not exist',
];
