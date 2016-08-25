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
    private $sortableResolver;

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

    public function __construct(PageableInterface $default, SortableResolver $sortableResolver, $parameterPage = 'page', $parameterSize = 'size')
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
        $this->sortableResolver = $sortableResolver;
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
        $this->validateIsScalar($page, $this->parameterPage);
        $size = $this->resolveParameterValue($this->parameterSize, $serverRequest);
        $this->validateIsScalar($size, $this->parameterSize);

        $sortable = $this->sortableResolver->resolveSilently($serverRequest);

        return new PageableRequest($page, $size, $sortable);
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
        $this->validateIsScalar($page, $this->parameterPage);
        if($page === null) {
            $page = $this->default->getPageNumber();
        }
        $size = $this->resolveParameterValueSilently($this->parameterSize, $serverRequest);
        $this->validateIsScalar($size, $this->parameterSize);
        if($size === null) {
            $size = $this->default->getPageSize();
        }

        $sortable = $this->sortableResolver->resolveSilently($serverRequest);
        return new PageableRequest($page, $size, $sortable);
    }
}
