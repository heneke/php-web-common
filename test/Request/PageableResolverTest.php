<?php
namespace Heneke\Web\Common\Request;

class PageableResolverTest extends AbstractResolverTest
{

    private $defaultPage = 1;
    private $defaultSize = 10;

    private $parameterPage = 'p';
    private $parameterSize = 's';

    /**
     * @var PageableResolver
     */
    private $resolver;

    /**
     * @before
     */
    public function before()
    {
        $this->resolver = new PageableResolver(new PageableRequest($this->defaultPage, $this->defaultSize), new SortResolver(), $this->parameterPage, $this->parameterSize);
    }

    /**
     * @test
     * @expectedException \Heneke\Web\Common\Request\UnresolvableException
     */
    public function unresolveable()
    {
        $this->resolver->resolve($this->createServerRequest('GET'));
    }

    /**
     * @test
     */
    public function resolveWithDefault()
    {
        $pageable = $this->resolver->resolveWithDefault($this->createServerRequest('GET'));
        $this->assertNotNull($pageable);
        $this->assertEquals($this->defaultPage, $pageable->getPageNumber());
        $this->assertEquals($this->defaultSize, $pageable->getPageSize());
    }

    /**
     * @test
     */
    public function resolve()
    {
        $page = 7;
        $size = 22;
        $pageable = $this->resolver->resolve($this->createServerRequest('GET', [$this->parameterPage => $page, $this->parameterSize => $size]));
        $this->assertNotNull($pageable);
        $this->assertEquals($page, $pageable->getPageNumber());
        $this->assertEquals($size, $pageable->getPageSize());
    }

    /**
     * @test
     * @expectedException \Heneke\Web\Common\Request\BadRequestException
     */
    public function resolveArrayValue()
    {
        $page = [7, 8];
        $size = 22;
        $this->resolver->resolve($this->createServerRequest('GET', [$this->parameterPage => $page, $this->parameterSize => $size]));
    }

    /**
     * @test
     * @expectedException \Heneke\Web\Common\Request\BadRequestException
     */
    public function resolveWithDefaultArrayValue()
    {
        $page = [7, 8];
        $size = 22;
        $this->resolver->resolveWithDefault($this->createServerRequest('GET', [$this->parameterPage => $page, $this->parameterSize => $size]));
    }
}