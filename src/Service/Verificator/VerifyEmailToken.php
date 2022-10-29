<?php

namespace App\Service\Verificator;

class VerifyEmailToken
{
    private $signingKey;

    public function __construct(string $key)
    {
        $this->signingKey = $key;
    }


    public function createToken(string $userId, string $email): string
    {
        $encodedData = json_encode([$userId, $email]);

        return base64_encode(hash_hmac('sha256', $encodedData, $this->signingKey, true));
    }
}
