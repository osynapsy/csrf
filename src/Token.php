<?php
namespace Osynapsy\Csrf;

class Token
{
    private string $secretKey;

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function generate(): array
    {
        $nonce = bin2hex(random_bytes(16));
        $token = hash_hmac('sha256', $nonce, $this->secretKey);
        return [
            'nonce' => $nonce,
            'token' => $token
        ];
    }

    public function check(?string $nonce, ?string $token): bool
    {
        if (empty($nonce) || empty($token)) {
            return false;
        }
        $expected = hash_hmac('sha256', $nonce, $this->secretKey);
        return hash_equals($expected, $token);
    }
}
