<?php
/**
 * Osynapsy CSRF Package
 *
 * Copyright (c) 2025 Pietro Celeste
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Osynapsy\Csrf;

/**
 * Class Token
 *
 * Generates and validates CSRF tokens using a secret key.
 * Each token is tied to a nonce and can be verified to prevent CSRF attacks.
 *
 * Usage:
 *   // Generate a new CSRF token pair
 *   $tokenManager = new Token($_ENV['CSRF_SECRET']);
 *   list($nonce, $token) = $tokenManager->generate();
 *
 *   // Check a submitted token
 *   if ($tokenManager->check($nonce, $token)) {
 *       // Token is valid
 *   } else {
 *       // Token is invalid
 *   }
 *
 * @package Osynapsy\Csrf
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class Token
{
    /**
     * @var string Secret key used to generate HMAC tokens.
     */
    private string $secretKey;

    /**
     * Token constructor.
     *
     * @param string $secretKey Secret key used to generate and validate CSRF tokens.
     */
    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * Generates a new CSRF token pair.
     *
     * Returns an array containing a unique nonce and a corresponding token.
     * The nonce ensures uniqueness per request and the token is generated
     * using HMAC based on the nonce and secret key.
     *
     * @return array Array containing [nonce, token].
     */
    public function generate(): array
    {
        $nonce = bin2hex(random_bytes(16));
        $token = hash_hmac('sha256', $nonce, $this->secretKey);
        return [
            'nonce' => $nonce,
            'token' => $token
        ];
    }

    /**
     * Validates a CSRF token against a nonce.
     *
     * Verifies that the provided token matches the expected value
     * generated using the nonce and the secret key.
     *
     * @param string|null $nonce The CSRF nonce received from the form submission.
     * @param string|null $token The CSRF token received from the form submission.
     * @return bool True if the token is valid, false otherwise.
     */
    public function check(?string $nonce, ?string $token): bool
    {
        if (empty($nonce) || empty($token)) {
            return false;
        }
        $expected = hash_hmac('sha256', $nonce, $this->secretKey);
        return hash_equals($expected, $token);
    }
}
