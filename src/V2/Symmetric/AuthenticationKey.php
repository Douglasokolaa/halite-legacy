<?php
declare(strict_types=1);
namespace ParagonIE\HaliteLegacy\V2\Symmetric;

use \ParagonIE\Halite\Alerts\InvalidKey;
use \ParagonIE\Halite\Util as CryptoUtil;

/**
 * Class AuthenticationKey
 * @package ParagonIE\Halite\Symmetric
 */
final class AuthenticationKey extends SecretKey
{
    /**
     * @param string $keyMaterial - The actual key data
     * @throws InvalidKey
     */
    public function __construct(string $keyMaterial = '')
    {
        if (CryptoUtil::safeStrlen($keyMaterial) !== \Sodium\CRYPTO_AUTH_KEYBYTES) {
            throw new InvalidKey(
                'Authentication key must be CRYPTO_AUTH_KEYBYTES bytes long'
            );
        }
        parent::__construct($keyMaterial);
        $this->is_signing_key = true;
    }
}
