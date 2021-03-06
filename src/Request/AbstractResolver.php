<?php
namespace HHIT\Web\Common\Request;

use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractResolver
{

    protected function resolveParameterValue($parameter, ServerRequestInterface $serverRequest)
    {
        // @codeCoverageIgnoreStart
        if (!$parameter) {
            throw new \InvalidArgumentException('Parameter required!');
        }
        // @codeCoverageIgnoreEnd

        if ($serverRequest->getQueryParams() != null && array_key_exists($parameter, $serverRequest->getQueryParams())) {
            return $serverRequest->getQueryParams()[$parameter];
        }

        if ($serverRequest->getParsedBody() != null && is_array($serverRequest->getParsedBody()) && array_key_exists($parameter, $serverRequest->getParsedBody())) {
            return $serverRequest->getParsedBody()[$parameter];
        }

        throw new UnresolvableException("Request does not contain parameter '{$parameter}'!");
    }

    protected function resolveParameterValueSilently($parameter, ServerRequestInterface $serverRequest)
    {
        try {
            return $this->resolveParameterValue($parameter, $serverRequest);
        } catch (UnresolvableException $e) {
            return null;
        }
    }

    protected function validateIsScalar($value, $parameter)
    {
        if (is_array($value)) {
            throw new BadRequestException("Parameter '{$parameter}' only supports scalar values!");
        }
    }
}
