<?php

namespace App\Services\JWTService;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use OpenSSLAsymmetricKey;

class JWTService
{
    public const ALGO = 'RS256';

    private OpenSSLAsymmetricKey $privateKey;

    private OpenSSLAsymmetricKey $publicKey;

    private array $payload;

    private function __construct()
    {
        $this->privateKey = $this->setPrivateKey();
        $this->publicKey = $this->setPublicKey();
    }

    public static function make(): JWTService
    {
        return new static();
    }

    public function setPayload($payload): JWTService
    {
        $this->payload = [
            'serverDomain' => request()->getHttpHost(),
            'expiresAt' => $this->expiresIn(),
            'uuid' => $payload,
        ];
        return $this;
    }

    public function getPrivateKey(): OpenSSLAsymmetricKey
    {
        return $this->privateKey;
    }

    public function setPrivateKey(): OpenSSLAsymmetricKey
    {
        return openssl_get_privatekey(file_get_contents(base_path(config('jwt.jwt_private_key_path'))), config('jwt.jwt_pass_phrase'));
    }

    public function getPublicKey(): OpenSSLAsymmetricKey
    {
        return $this->publicKey;
    }

    public function setPublicKey(): OpenSSLAsymmetricKey
    {
        return openssl_get_publickey(file_get_contents(base_path(config('jwt.jwt_public_key_path'))));
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function createToken(): string
    {
        return JWT::encode($this->getPayload(), $this->getPrivateKey(), self::ALGO);
    }

    public function decodeToken($token): array
    {
        $decoded = JWT::decode($token, new Key($this->getPublicKey(), self::ALGO));
        return (array) $decoded;
    }

    public function expiresIn(): float|int
    {
        return time() + 60 * 60 * 24 * config('jwt.jwt_expires_in_day');
    }
}
