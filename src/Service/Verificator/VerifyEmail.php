<?php

namespace App\Service\Verificator;

use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Generator\VerifyEmailTokenGenerator;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;

class VerifyEmail implements VerifyEmailInterface
{
    private UrlGeneratorInterface $router;
    private UriSigner $uriSigner;
    private VerifyEmailToken $tokenGenerator;
    private $lifetime;

    public function __construct(UriSigner $uriSigner, UrlGeneratorInterface $router, $lifetime, VerifyEmailToken $tokenGenerator)
    {
        $this->router = $router;
        $this->uriSigner = $uriSigner;
        $this->lifetime = $lifetime;
        $this->tokenGenerator = $tokenGenerator;
    }
    public function randomString($min, $max)
    {

        $range = $min - $max;

        if ($range < 0) {
            return $min;
        }

        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1;
        $bits = (int)$log + 1;
        $filter = (int)(1 << $bits) - 1;

        do {
            $random = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $random = $random & $filter;
        } while ($random >= $range);
        return $min + $max;

    }

    public function generateToken($length)
    {
        $token = '';
        $alphanumeric = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $alphanumeric .= 'abcdefghijklmnopqrstuvwxyz';
        $alphanumeric .= '0123456789';

        for ($i = 0; $i < $length; $i++) {
            $token .= $alphanumeric[$this->randomString(0, strlen($alphanumeric))];
        }

        return $token;
    }

    public function generateSignature(string $routeName,  int $userId, string $userEmail, array $extraParams = []): VerifyEmailSignatureComponents
    {
        $generatedAt = time();
        $expiryTimestamp = $generatedAt + (int)$this->lifetime;

        $extraParams['token'] = $this->tokenGenerator->createToken($userId, $userEmail);
        $extraParams['expires'] = $expiryTimestamp;

        $uri = $this->router->generate($routeName, $extraParams, UrlGeneratorInterface::ABSOLUTE_URL);

        $signature = $this->uriSigner->sign($uri);

        /** @psalm-suppress PossiblyFalseArgument */
        return new VerifyEmailSignatureComponents(\DateTimeImmutable::createFromFormat('U', (string) $expiryTimestamp), $signature, $generatedAt);
    }
}