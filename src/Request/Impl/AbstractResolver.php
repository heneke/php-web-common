<?php
namespace Heneke\Web\Common\Request\Impl;

use Heneke\Web\Common\Request\UnresolvableException;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractResolver
{

    protected function resolveParameterValue($parameter, ServerRequestInterface $serverRequest)
    {
        if (!$parameter) {
            throw new \InvalidArgumentException('Parameter required!');
        }

        if ($serverRequest->getQueryParams() != null && array_key_exists($parameter, $serverRequest->getQueryParams())) {
            return $serverRequest->getQueryParams()[$parameter];
        }

        if ($serverRequest->getParsedBody() != null && is_array($serverRequest->getParsedBody()) && array_key_exists($parameter, $serverRequest->getParsedBody())) {
            return $serverRequest->getParsedBody()[$parameter];
        }

        throw new UnresolvableException("Request does not contain parameter '{$parameter}'!");
    }
}