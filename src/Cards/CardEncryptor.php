<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Cards;

use Dominasys\PagBank\Cards\Dto\CardEncryptData;
use Dominasys\PagBank\Cards\Response\CardEncryptionError;
use Dominasys\PagBank\Cards\Response\CardEncryptionResult;
use Normalizer;

final readonly class CardEncryptor
{
    private const string ERROR_NUMBER = 'INVALID_NUMBER';

    private const string ERROR_SECURITY_CODE = 'INVALID_SECURITY_CODE';

    private const string ERROR_EXP_MONTH = 'INVALID_EXPIRATION_MONTH';

    private const string ERROR_EXP_YEAR = 'INVALID_EXPIRATION_YEAR';

    private const string ERROR_PUBLIC_KEY = 'INVALID_PUBLIC_KEY';

    private const string ERROR_HOLDER = 'INVALID_HOLDER';

    public function encrypt(CardEncryptData $data): CardEncryptionResult
    {
        $sanitized = $this->sanitize($data);
        $errors = $this->validate($sanitized);

        if ($errors !== []) {
            return new CardEncryptionResult(null, $errors);
        }

        $publicKey = $this->normalizePublicKey($sanitized['publicKey']);

        if ($publicKey === null) {
            return new CardEncryptionResult(null, [
                new CardEncryptionError(self::ERROR_PUBLIC_KEY, 'invalid `publicKey`'),
            ]);
        }

        $payload = sprintf(
            '%s;%s;%s;%s;%s;%d',
            $sanitized['number'],
            $sanitized['securityCode'],
            $sanitized['expMonth'],
            $sanitized['expYear'],
            $sanitized['holder'],
            (int) floor(microtime(true) * 1000),
        );

        $encrypted = null;

        if (!openssl_public_encrypt($payload, $encrypted, $publicKey, OPENSSL_PKCS1_PADDING)) {
            return new CardEncryptionResult(null, [
                new CardEncryptionError(self::ERROR_PUBLIC_KEY, 'invalid `publicKey`'),
            ]);
        }

        return new CardEncryptionResult(base64_encode((string) $encrypted));
    }

    /**
     * @return array{publicKey: string, number: string, securityCode: string, expMonth: string, expYear: string, holder: string}
     */
    private function sanitize(CardEncryptData $data): array
    {
        return [
            'publicKey' => trim($data->publicKey),
            'number' => trim($data->number),
            'securityCode' => trim($data->securityCode ?? ''),
            'expMonth' => $this->formatExpMonth($data->expMonth),
            'expYear' => trim((string) $data->expYear),
            'holder' => $this->sanitizeHolder($data->holder),
        ];
    }

    /**
     * @param  array{publicKey: string, number: string, securityCode: string, expMonth: string, expYear: string, holder: string}  $card
     * @return array<int, CardEncryptionError>
     */
    private function validate(array $card): array
    {
        $errors = [];

        if (!$this->isNumberWithLength($card['number'], 13, 19)) {
            $errors[] = new CardEncryptionError(self::ERROR_NUMBER, 'invalid field `number`. You must pass a value between 13 and 19 digits');
        }

        if ($card['securityCode'] !== '' && !$this->isNumberWithLength($card['securityCode'], 3, 4)) {
            $errors[] = new CardEncryptionError(self::ERROR_SECURITY_CODE, 'invalid field `securityCode`. You must pass a value with 3, 4 or none digits');
        }

        if (!ctype_digit($card['expMonth']) || (int) $card['expMonth'] < 1 || (int) $card['expMonth'] > 12) {
            $errors[] = new CardEncryptionError(self::ERROR_EXP_MONTH, 'invalid field `expMonth`. You must pass a value between 1 and 12');
        }

        if (!ctype_digit($card['expYear']) || (int) $card['expYear'] < 1900 || (int) $card['expYear'] > 2099) {
            $errors[] = new CardEncryptionError(self::ERROR_EXP_YEAR, 'invalid field `expYear`. You must pass a value with 4 digits');
        }

        if ($card['publicKey'] === '') {
            $errors[] = new CardEncryptionError(self::ERROR_PUBLIC_KEY, 'invalid `publicKey`');
        }

        if ($card['holder'] === '' || preg_match('/\d/', $card['holder']) === 1) {
            $errors[] = new CardEncryptionError(self::ERROR_HOLDER, 'invalid `holder`');
        }

        return $errors;
    }

    private function formatExpMonth(int $expMonth): string
    {
        $month = trim((string) $expMonth);

        return strlen($month) === 1 ? '0' . $month : $month;
    }

    private function sanitizeHolder(string $input): string
    {
        $holder = str_replace(["'", '/'], '', trim($input));
        $normalized = Normalizer::normalize($holder, Normalizer::FORM_D);

        if ($normalized !== false) {
            $holder = $normalized;
        }

        $holder = preg_replace('/[\x{0300}-\x{036f}]/u', '', $holder) ?? $holder;
        $holder = preg_replace('/[^a-zA-Z\s]/', '', $holder) ?? $holder;

        return substr($holder, 0, 30);
    }

    private function normalizePublicKey(string $publicKey): ?string
    {
        if ($publicKey === '') {
            return null;
        }

        $resource = openssl_pkey_get_public($publicKey);

        if ($resource !== false) {
            return $publicKey;
        }

        $wrapped = $this->wrapPublicKey($publicKey);
        $resource = openssl_pkey_get_public($wrapped);

        if ($resource === false) {
            return null;
        }

        return $wrapped;
    }

    private function wrapPublicKey(string $publicKey): string
    {
        $compact = preg_replace('/\s+/', '', trim($publicKey)) ?? trim($publicKey);
        $chunked = trim(chunk_split($compact, 64, "\n"));

        return "-----BEGIN PUBLIC KEY-----\n" . $chunked . "\n-----END PUBLIC KEY-----";
    }

    private function isNumberWithLength(string $value, int $minLength, int $maxLength): bool
    {
        return $value !== '' && ctype_digit($value) && strlen($value) >= $minLength && strlen($value) <= $maxLength;
    }
}
