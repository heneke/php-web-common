<?php
namespace HHIT\Web\Common\Request;

use Exception;

class BadRequestException extends \RuntimeException
{
    public function __construct($message, Exception $previous = null)
    {
        parent::__construct($message, 400, $previous);
    }
}
