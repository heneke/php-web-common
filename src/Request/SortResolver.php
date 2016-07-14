<?php
namespace Heneke\Web\Common\Request;

use Psr\Http\Message\ServerRequestInterface;

class SortResolver extends AbstractResolver
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
     * @throws UnresolvableException
     * @return SortInterface[]
     */
    public function resolve(ServerRequestInterface $serverRequest)
    {
        $value = $this->resolveParameterValue($this->parameter, $serverRequest);
        $sorts = [];
        if (is_array($value)) {
            foreach ($value as $input) {
                $sorts[] = SortRequest::fromString($input);
            }
        } else {
            $sorts[] = SortRequest::fromString($value);
        }
        return $sorts;
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @return SortInterface[]
     */
    public function resolveSilently(ServerRequestInterface $serverRequest)
    {
        try {
            return $this->resolve($serverRequest);
        } catch (UnresolvableException $e) {
            return [];
        }
    }
}
