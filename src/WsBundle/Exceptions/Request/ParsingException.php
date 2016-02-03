<?php
declare(strict_types=1);

namespace WsBundle\Exceptions\Request;

/**
 * Class ParsingException
 * @package AppBundle\Exceptions\Request
 */
class ParsingException extends \Exception
{

    protected $details;

    /**
     * ParsingException constructor.
     * @param string $message
     * @param array $details
     */
    public function __construct(string $message, array $details = [])
    {
        $this->details = $details;
        parent::__construct($message, 0, null);
    }

    /**
     * @return mixed
     */
    public function getExceptionDetails()
    {
        return $this->details;
    }

}
