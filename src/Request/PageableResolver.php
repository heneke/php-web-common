<?php
namespace Heneke\Web\Common\Request;

use Psr\Http\Message\ServerRequestInterface;

class PageableResolver extends AbstractResolver
{
    /**
     * @var PageableInterface
     */
    private $default;

    /**
     * @var SortResolver
     */
    private $sortResolver;

    /**
     * The request parameter to resolve the page
     * @var string
     */
    private $parameterPage;

    /**
     * The request parameter to resolve the page size
     * @var string
     */
    private $parameterSize;

    public function __construct(PageableInterface $default, SortResolver $sortResolver, $parameterPage = 'page', $parameterSize = 'size')
    {
        if (!$parameterPage) {
            throw new \InvalidArgumentException('Parameter for page required!');
        }
        if (!$parameterSize) {
            throw new \InvalidArgumentException('Parameter for size required!');
        }
        if ($parameterPage == $parameterSize) {
            throw new \InvalidArgumentException('Different parameters for page and size required!');
        }

        $this->default = $default;
        $this->sortResolver = $sortResolver;
        $this->parameterPage = $parameterPage;
        $this->parameterSize = $parameterSize;
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @throws UnresolvableException
     * @throws BadRequestException
     * @return PageableInterface
     */
    public function resolve(ServerRequestInterface $serverRequest)
    {
        $page = $this->resolveParameterValue($this->parameterPage, $serverRequest);
        if (is_array($page)) {
            throw new BadRequestException("Parameter '{$this->parameterSize}' only supports scalar values!");
        }
        $size = $this->resolveParameterValue($this->parameterSize, $serverRequest);
        if (is_array($size)) {
            throw new BadRequestException("Parameter '{$this->parameterSize}' only supports scalar values!");
        }

        $sorting = $this->sortResolver->resolveSilently($serverRequest);

        return new PageableRequest($page, $size, $sorting);
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @throws BadRequestException
     * @return PageableInterface
     */
    public function resolveWithDefault(ServerRequestInterface $serverRequest)
    {
        try {
            return $this->resolve($serverRequest);
        } catch (UnresolvableException $e) {
            return $this->default;
        }
    }
}