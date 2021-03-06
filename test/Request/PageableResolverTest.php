<?php
namespace HHIT\Web\Common\Request;

class PageableResolverTest extends AbstractResolverTest
{

    private $defaultPage = 23;
    private $defaultSize = 33;

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
        $this->resolver = new PageableResolver(new PageableRequest($this->defaultPage, $this->defaultSize), new SortableResolver(new SortResolver()), $this->parameterPage, $this->parameterSize);
    }

    /**
     * @test
     * @expectedException \HHIT\Web\Common\Request\UnresolvableException
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
     * @expectedException \HHIT\Web\Common\Request\BadRequestException
     * @expectedExceptionMessage Parameter 'p' only supports
     */
    public function resolveArrayValue()
    {
        $page = [7, 8];
        $size = 22;
        $this->resolver->resolve($this->createServerRequest('GET', [$this->parameterPage => $page, $this->parameterSize => $size]));
    }

    /**
     * @test
     * @expectedException \HHIT\Web\Common\Request\BadRequestException
     * @expectedExceptionMessage Parameter 'p' only supports
     */
    public function resolveWithDefaultArrayValue()
    {
        $page = [7, 8];
        $size = 22;
        $this->resolver->resolveWithDefault($this->createServerRequest('GET', [$this->parameterPage => $page, $this->parameterSize => $size]));
    }

    /**
     * @test
     */
    public function resolveWithDefaultPage()
    {
        $size = 33;
        $pageable = $this->resolver->resolveWithDefault($this->createServerRequest('GET', [$this->parameterSize => $size]));
        $this->assertNotNull($pageable);
        $this->assertEquals($size, $pageable->getPageSize());
        $this->assertEquals($this->defaultPage, $pageable->getPageNumber());
    }

    /**
     * @test
     */
    public function resolveWithDefaultSize()
    {
        $page = 44;
        $pageable = $this->resolver->resolveWithDefault($this->createServerRequest('GET', [$this->parameterPage => $page]));
        $this->assertNotNull($pageable);
        $this->assertEquals($page, $pageable->getPageNumber());
        $this->assertEquals($this->defaultSize, $pageable->getPageSize());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Parameter for page
     */
    public function createInvalidParameterPage()
    {
        new PageableResolver(new PageableRequest(1, 10), new SortableResolver(new SortResolver()), '', 'size');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Parameter for size
     */
    public function createInvalidParameterSize()
    {
        new PageableResolver(new PageableRequest(1, 10), new SortableResolver(new SortResolver()), 'page', '');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Different parameters
     */
    public function createInvalidParameters()
    {
        new PageableResolver(new PageableRequest(1, 10), new SortableResolver(new SortResolver()), 'x', 'x');
    }
}
