<?php

namespace App\Service\Verificator;

use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;

interface VerifyEmailInterface
{
    public function generateSignature(string $routeName, int $userId, string $userEmail, array $extraParams = []): VerifyEmailSignatureComponents;
}
