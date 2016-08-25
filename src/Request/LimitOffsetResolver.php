<?php
namespace Heneke\Web\Common\Request;

use Psr\Http\Message\ServerRequestInterface;

class LimitOffsetResolver extends AbstractResolver
{

    /**
     * @var LimitOffsetInterface
     */
    private $default;

    /**
     * @var SortableResolver
     */
    private $sortableResolver;

    /**
     * @var string
     */
    private $parameterLimit;

    /**
     * @var string
     */
    private $parameterOffset;

    public function __construct(LimitOffsetInterface $default, SortableResolver $sortableResolver, $parameterLimit = 'limit', $parameterOffset = 'offset')
    {
        if (!$parameterLimit) {
            throw new \InvalidArgumentException('Parameter for limit required!');
        }
        if (!$parameterOffset) {
            throw new \InvalidArgumentException('Parameter for offset required!');
        }
        if ($parameterLimit == $parameterOffset) {
            throw new \InvalidArgumentException('Different parameters for limit and offset required!');
        }

        $this->default = $default;
        $this->sortableResolver = $sortableResolver;
        $this->parameterLimit = $parameterLimit;
        $this->parameterOffset = $parameterOffset;
    }

    /**
     * Resolves the LimitOffset from the given server request. Fails if any parameter is missing.
     *
     * @param ServerRequestInterface $serverRequest
     * @throws UnresolvableException if any parameter is missing
     * @throws BadRequestException if request parameters are not scalar
     * @return LimitOffsetInterface
     */
    public function resolve(ServerRequestInterface $serverRequest)
    {
        $limit = $this->resolveParameterValue($this->parameterLimit, $serverRequest);
        $this->validateIsScalar($limit, $this->parameterLimit);
        $offset = $this->resolveParameterValue($this->parameterOffset, $serverRequest);
        $this->validateIsScalar($offset, $this->parameterOffset);

        $sortable = $this->sortableResolver->resolveSilently($serverRequest);
        return new LimitOffsetRequest($limit, $offset, $sortable);
    }

    /**
     * Resolves the LimitOffset from the given server request using the default values if any parameter is missing.
     *
     * @param ServerRequestInterface $serverRequest
     * @throws BadRequestException if request paramaters are not scalar
     * @return LimitOffsetInterface
     */
    public function resolveWithDefault(ServerRequestInterface $serverRequest)
    {
        $limit = $this->resolveParameterValueSilently($this->parameterLimit, $serverRequest);
        $this->validateIsScalar($limit, $this->parameterLimit);
        if ($limit === null) {
            $limit = $this->default->getLimit();
        }
        $offset = $this->resolveParameterValueSilently($this->parameterOffset, $serverRequest);
        $this->validateIsScalar($offset, $this->parameterOffset);
        if ($offset === null) {
            $offset = $this->default->getOffset();
        }

        $sortable = $this->sortableResolver->resolveSilently($serverRequest);
        return new LimitOffsetRequest($limit, $offset, $sortable);
    }
}
