<?php
namespace HHIT\Web\Common\Request;

use Psr\Http\Message\ServerRequestInterface;

class SortableResolver
{

    private $sortResolver;

    public function __construct(SortResolver $sortResolver)
    {
        $this->sortResolver = $sortResolver;
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @return SortableRequest
     */
    public function resolve(ServerRequestInterface $serverRequest)
    {
        return new SortableRequest($this->sortResolver->resolve($serverRequest));
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @return SortableRequest
     */
    public function resolveSilently(ServerRequestInterface $serverRequest)
    {
        return new SortableRequest($this->sortResolver->resolveSilently($serverRequest));
    }
}
