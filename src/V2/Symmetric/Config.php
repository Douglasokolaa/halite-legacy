<?php
declare(strict_types=1);
namespace ParagonIE\HaliteLegacy\V2\Symmetric;

use \ParagonIE\HaliteLegacy\V2\{
    Alerts as CryptoException,
    Config as BaseConfig
};

/**
 * Class Config
 * @package ParagonIE\HaliteLegacy\V2\Symmetric
 */
final class Config extends BaseConfig
{
    /**
     * Get the configuration
     * 
     * @param string $header
     * @param string $mode
     * @return Config
     * @throws CryptoException\InvalidMessage
     */
    public static function getConfig(
        string $header,
        string $mode = 'encrypt'
    ): self {
        if (\ord($header[0]) !== 49 || \ord($header[1]) !== 66) {
            throw new CryptoException\InvalidMessage(
                'Invalid version tag'
            );
        }
        $major = \ord($header[2]);
        $minor = \ord($header[3]);
        if ($mode === 'encrypt') {
            return new Config(
                self::getConfigEncrypt($major, $minor)
            );
        } elseif ($mode === 'auth') {
            return new Config(
                self::getConfigAuth($major, $minor)
            );
        }
        throw new CryptoException\InvalidMessage(
            'Invalid configuration mode: '.$mode
        );
    }
    
    /**
     * Get the configuration for encrypt operations
     * 
     * @param int $major
     * @param int $minor
     * @return array
     * @throws CryptoException\InvalidMessage
     */
    public static function getConfigEncrypt(int $major, int $minor): array
    {
        if ($major === 1) {
            switch ($minor) {
                case 0:
                    return [
                        'HKDF_SALT_LEN' => 32,
                        'SHORTEST_CIPHERTEXT_LENGTH' => 92,
                        'NONCE_BYTES' => \Sodium\CRYPTO_STREAM_NONCEBYTES,
                        'MAC_SIZE' => \Sodium\CRYPTO_AUTH_BYTES,
                        'MAC_ALGO' => 'HMAC-SHA512/256',
                        'HKDF_SBOX' => 'Halite|EncryptionKey',
                        'HKDF_AUTH' => 'AuthenticationKeyFor_|Halite'
                    ];
            }
        } elseif ($major === 2) {
            switch ($minor) {
                case 1:
                case 0:
                    return [
                        'SHORTEST_CIPHERTEXT_LENGTH' => 124,
                        'NONCE_BYTES' => \Sodium\CRYPTO_STREAM_NONCEBYTES,
                        'HKDF_SALT_LEN' => 32,
                        'MAC_ALGO' => 'BLAKE2b',
                        'MAC_SIZE' => \Sodium\CRYPTO_GENERICHASH_BYTES_MAX,
                        'HKDF_SBOX' => 'Halite|EncryptionKey',
                        'HKDF_AUTH' => 'AuthenticationKeyFor_|Halite'
                    ];
            }
        }
        throw new CryptoException\InvalidMessage(
            'Invalid version tag'
        );
    }
    
    /**
     * Get the configuration for seal operations
     * 
     * @param int $major
     * @param int $minor
     * @return array
     * @throws CryptoException\InvalidMessage
     */
    public static function getConfigAuth(int $major, int $minor): array
    {
        if ($major === 1) {
            switch ($minor) {
                case 0:
                    return [
                        'HKDF_SALT_LEN' => 32,
                        'MAC_ALGO' => 'HMAC-SHA512/256',
                        'MAC_SIZE' => \Sodium\CRYPTO_AUTH_BYTES,
                        'PUBLICKEY_BYTES' => \Sodium\CRYPTO_BOX_PUBLICKEYBYTES,
                        'HKDF_SBOX' => 'Halite|EncryptionKey',
                        'HKDF_AUTH' => 'AuthenticationKeyFor_|Halite'
                    ];
            }
        } elseif ($major === 2) {
            switch ($minor) {
                case 1:
                case 0:
                    return [
                        'HKDF_SALT_LEN' => 32,
                        'MAC_ALGO' => 'BLAKE2b',
                        'MAC_SIZE' => \Sodium\CRYPTO_GENERICHASH_BYTES_MAX,
                        'PUBLICKEY_BYTES' => \Sodium\CRYPTO_BOX_PUBLICKEYBYTES,
                        'HKDF_SBOX' => 'Halite|EncryptionKey',
                        'HKDF_AUTH' => 'AuthenticationKeyFor_|Halite'
                    ];
            }
        }
        throw new CryptoException\InvalidMessage(
            'Invalid version tag'
        );
    }
}
