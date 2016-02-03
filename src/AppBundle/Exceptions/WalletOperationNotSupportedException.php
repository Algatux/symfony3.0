<?php
declare(strict_types=1);

namespace AppBundle\Exceptions;

/**
 * Class WalletOperationNotSupportedException
 * @package AppBundle\Exceptions
 */
class WalletOperationNotSupportedException extends \Exception
{

    /**
     * WalletOperationNotSupportedException constructor.
     * @param string $operation
     */
    public function __construct(string $operation)
    {

        $message = "Operation %s is not suppoerted on wallets";

        parent::__construct(sprintf($message, $operation), 0, null);

    }
    
}