<?php


return [
    \Illuminate\Http\Response::HTTP_UNAUTHORIZED => 'Unauthorized',
    \Illuminate\Http\Response::HTTP_FORBIDDEN => 'Access denied',
    \Illuminate\Http\Response::HTTP_NOT_FOUND => 'Page not found',
    'invalid_email_or_password' => 'Invalid email or password.',
    'jwt_exception' => 'Failed to create token. Error:',
    'token_expired' => 'Token expired. Error:',
    'token_invalid' => 'Token invalid. Error:',
    'offer_not_found' => 'The offer you are looking for does not exist, or it is on moderation',
    'bad_activation_code' => 'Activation code invalid.'
];
