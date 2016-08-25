<?php
namespace Heneke\Web\Common\Request;

class SortableResolverTest extends AbstractResolverTest
{

    /**
     * @var SortableResolver
     */
    private $resolver;

    /**
     * @before
     */
    public function before()
    {
        $this->resolver = new SortableResolver(new SortResolver('sort'));
    }

    /**
     * @test
     */
    public function resolve()
    {
        $s = $this->resolver->resolve($this->createServerRequest('GET', ['sort' => 'prop']));
        $this->assertNotNull($s);
        $this->assertCount(1, $s->getSorting());
    }

    /**
     * @test
     */
    public function resolveSilently()
    {
        $s = $this->resolver->resolveSilently($this->createServerRequest('GET', []));
        $this->assertNotNull($s);
        $this->assertCount(0, $s->getSorting());
    }
}
