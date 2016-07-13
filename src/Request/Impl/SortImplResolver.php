<?php
namespace Heneke\Web\Common\Request\Impl;

use Heneke\Web\Common\Request\SortInterface;
use Psr\Http\Message\ServerRequestInterface;

class SortImplResolver extends AbstractResolver
{

    /**
     * The request parameter to resolve by
     *
     * @var string
     */
    private $parameter;

    public function __construct($parameter = 'sort')
    {
        if (!$parameter) {
            throw new \InvalidArgumentException('Parameter required!');
        }
        $this->parameter = $parameter;
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @return SortInterface[]
     */
    public function resolve(ServerRequestInterface $serverRequest)
    {
        $value = $this->resolveParameterValue($this->parameter, $serverRequest);
        $sorts = [];
        if (is_array($value)) {
            foreach ($value as $input) {
                $sorts[] = SortImpl::fromString($input);
            }
        } else {
            $sorts[] = SortImpl::fromString($value);
        }
        return $sorts;
    }
}