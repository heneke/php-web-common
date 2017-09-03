<?php
namespace HHIT\Web\Common\Request;

use Exception;

class UnresolvableException extends BadRequestException
{
    public function __construct($message, Exception $previous = null)
    {
        parent::__construct($message, $previous);
    }
}
