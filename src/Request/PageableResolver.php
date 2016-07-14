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
     * Resolves the Pageable from the given server request. Fails if any parameters are missing.
     *
     * @param ServerRequestInterface $serverRequest
     * @throws UnresolvableException if any parameter is missing
     * @throws BadRequestException if request parameters are not scalar
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
     * Resolves the Pageable from the given server request, using the default values if any parameter is missing.
     *
     * @param ServerRequestInterface $serverRequest
     * @throws BadRequestException if request parameters are not scalar
     * @return PageableInterface
     */
    public function resolveWithDefault(ServerRequestInterface $serverRequest)
    {
        $page = $this->resolveParameterValueSilently($this->parameterPage, $serverRequest);
        if (is_array($page)) {
            throw new BadRequestException("Parameter '{$this->parameterSize}' only supports scalar values!");
        }
        if($page === null) {
            $page = $this->default->getPageNumber();
        }
        $size = $this->resolveParameterValueSilently($this->parameterSize, $serverRequest);
        if (is_array($size)) {
            throw new BadRequestException("Parameter '{$this->parameterSize}' only supports scalar values!");
        }
        if($size === null) {
            $size = $this->default->getPageSize();
        }

        $sorting = $this->sortResolver->resolveSilently($serverRequest);
        return new PageableRequest($page, $size, $sorting);
    }
}
