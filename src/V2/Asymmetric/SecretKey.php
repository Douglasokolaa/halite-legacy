<?php
namespace ParagonIE\HaliteLegacy\V2\Asymmetric;

use \ParagonIE\HaliteLegacy\V2\Contract;
use \ParagonIE\HaliteLegacy\V2\Key;
use \ParagonIE\HaliteLegacy\V2\Alerts\CannotPerformOperation;

/**
 * Class SecretKey
 * @package ParagonIE\HaliteLegacy\V2\Asymmetric
 */
class SecretKey extends Key
{
    /**
     * @param string $keyMaterial - The actual key data
     */
    public function __construct(string $keyMaterial = '')
    {
        parent::__construct($keyMaterial);
        $this->is_asymmetric_key = true;
    }
    
    /**
     * See the appropriate derived class.
     */
    public function derivePublicKey()
    {
        throw new CannotPerformOperation(
            'This is not implemented in the base class'
        );
    }
}
