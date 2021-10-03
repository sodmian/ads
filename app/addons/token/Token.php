<?php

namespace Addons\Token;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\UnencryptedToken;

class Token
{
    public Configuration $tokenCfg;
    const JWT = '4f1g23a12aa';

    public function __construct()
    {
        $this->tokenCfg = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::base64Encoded($_ENV['API_KEY_SECRET'])
        );
    }

    private function getAuthorizationHeader(): ?string
    {
        $headers = null;

        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));

            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    public function getBearerToken()
    {
        $headers = $this->getAuthorizationHeader();

        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    public function builder(): \Lcobucci\JWT\Token\Plain
    {
        return $this->tokenCfg->builder()
            ->identifiedBy($this::JWT)
            ->getToken($this->tokenCfg->signer(), $this->tokenCfg->signingKey());
    }

    public function isValidateBearer(): bool
    {
        $token = $this->builder();
        $requestToken = $this->getBearerToken();

        if (!$requestToken) {
            throw new \RuntimeException('Bearer Authentication Failed', 403);
        }

        $requestToken = $this->tokenCfg->parser()->parse($requestToken);
        assert($requestToken instanceof UnencryptedToken);

        return $token->toString() == $requestToken->toString();
    }

    public function generateToken()
    {
        if (session_id() === '')
            session_start();
        if (!isset($_SESSION['token']))
            $_SESSION['token'] = bin2hex(random_bytes(16));

        return $_SESSION['token'];
    }

    public function isValidateToken(string $requestToken): bool
    {
        return isset($_SESSION['token']) && $_SESSION['token'] == $requestToken;
    }
}