<?php
namespace HHIT\Web\Common\Request;

class LimitOffsetResolverTest extends AbstractResolverTest
{

    private $defaultLimit = 27;
    private $defaultOffset = 37;

    private $parameterLimit = 'l';
    private $parameterOffset = 'o';

    /**
     * @var LimitOffsetResolver
     */
    private $resolver;

    /**
     * @before
     */
    public function before()
    {
        $this->resolver = new LimitOffsetResolver(new LimitOffsetRequest($this->defaultLimit, $this->defaultOffset), new SortableResolver(new SortResolver()), $this->parameterLimit, $this->parameterOffset);
    }

    /**
     * @test
     */
    public function resolve()
    {
        $limit = 44;
        $offset = 33;
        $lo = $this->resolver->resolve($this->createServerRequest('GET', [$this->parameterLimit => $limit, $this->parameterOffset => $offset]));
        $this->assertNotNull($lo);
        $this->assertEquals($limit, $lo->getLimit());
        $this->assertEquals($offset, $lo->getOffset());
    }

    /**
     * @test
     * @expectedException \HHIT\Web\Common\Request\UnresolvableException
     */
    public function unresolvable()
    {
        $this->resolver->resolve($this->createServerRequest('GET'));
    }

    /**
     * @test
     */
    public function resolveWithDefaultLimit()
    {
        $offset = 88;
        $lo = $this->resolver->resolveWithDefault($this->createServerRequest('GET', [$this->parameterOffset => $offset]));
        $this->assertNotNull($lo);
        $this->assertEquals($offset, $lo->getOffset());
        $this->assertEquals($this->defaultLimit, $lo->getLimit());
    }

    /**
     * @test
     */
    public function resolveWithDefaultOffset()
    {
        $limit = 99;
        $lo = $this->resolver->resolveWithDefault($this->createServerRequest('GET', [$this->parameterLimit => $limit]));
        $this->assertNotNull($lo);
        $this->assertEquals($limit, $lo->getLimit());
        $this->assertEquals($this->defaultOffset, $lo->getOffset());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Parameter for limit
     */
    public function createInvalidParameterLimit()
    {
        new LimitOffsetResolver(new LimitOffsetRequest(10, 0), new SortableResolver(new SortResolver()), '', 'offset');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Parameter for offset
     */
    public function createInvalidParameterOffset()
    {
        new LimitOffsetResolver(new LimitOffsetRequest(10, 0), new SortableResolver(new SortResolver()), 'limit', '');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Different parameters
     */
    public function createInvalidParameters()
    {
        new LimitOffsetResolver(new LimitOffsetRequest(10, 0), new SortableResolver(new SortResolver()), 'x', 'x');
    }

    /**
     * @test
     * @expectedException \HHIT\Web\Common\Request\BadRequestException
     * @expectedExceptionMessage Parameter 'l' only supports
     */
    public function nonScalarLimit()
    {
        $this->resolver->resolve($this->createServerRequest('GET', [$this->parameterLimit => [1, 2], $this->parameterOffset => 10]));
    }

    /**
     * @test
     * @expectedException \HHIT\Web\Common\Request\BadRequestException
     * @expectedExceptionMessage Parameter 'o' only supports
     */
    public function nonScalarOffset()
    {
        $this->resolver->resolve($this->createServerRequest('GET', [$this->parameterLimit => 10, $this->parameterOffset => [1, 2]]));
    }
}