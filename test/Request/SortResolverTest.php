<?php
namespace HHIT\Web\Common\Request;

class SortResolverTest extends AbstractResolverTest
{

    private $parameter = 'sort';

    /**
     * @var SortResolver
     */
    private $resolver;

    /**
     * @before
     */
    public function before()
    {
        $this->resolver = new SortResolver($this->parameter);
    }

    /**
     * @test
     * @expectedException \HHIT\Web\Common\Request\UnresolvableException
     * @expectedExceptionMessage Request does not contain
     */
    public function unresolvable()
    {
        $this->resolver->resolve($this->createServerRequest('POST'));
    }

    /**
     * @test
     */
    public function resolveQueryScalar()
    {
        $prop = 'prop';
        $sorts = $this->resolver->resolve($this->createServerRequest('GET', [$this->parameter => $prop]));
        $this->assertNotNull($sorts);
        $this->assertTrue(is_array($sorts));
        $this->assertCount(1, $sorts);

        $sort = $sorts[0];
        $this->assertEquals($prop, $sort->getProperty());
    }

    /**
     * @test
     */
    public function resolveQueryArray()
    {
        $props = ['a', 'b'];
        $sorts = $this->resolver->resolve($this->createServerRequest('GET', [$this->parameter => $props]));
        $this->assertNotNull($sorts);
        $this->assertTrue(is_array($sorts));
        $this->assertCount(2, $sorts);

        $sort = $sorts[0];
        $this->assertEquals('a', $sort->getProperty());
        $sort = $sorts[1];
        $this->assertEquals('b', $sort->getProperty());
    }

    /**
     * @test
     */
    public function resolvePostScalar()
    {
        $prop = 'prop';
        $sorts = $this->resolver->resolve($this->createServerRequest('POST', null, [$this->parameter => $prop]));
        $this->assertNotNull($sorts);
        $this->assertTrue(is_array($sorts));
        $this->assertCount(1, $sorts);

        $sort = $sorts[0];
        $this->assertEquals($prop, $sort->getProperty());
    }

    /**
     * @test
     */
    public function resolvePostArray()
    {
        $props = ['a', 'b'];
        $sorts = $this->resolver->resolve($this->createServerRequest('POST', null, [$this->parameter => $props]));
        $this->assertNotNull($sorts);
        $this->assertTrue(is_array($sorts));
        $this->assertCount(2, $sorts);

        $sort = $sorts[0];
        $this->assertEquals('a', $sort->getProperty());
        $sort = $sorts[1];
        $this->assertEquals('b', $sort->getProperty());
    }

    /**
     * @test
     */
    public function resolveSilently()
    {
        $sorts = $this->resolver->resolveSilently($this->createServerRequest('GET'));
        $this->assertTrue(is_array($sorts));
        $this->assertCount(0, $sorts);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Parameter required
     */
    public function createInvalidParameter()
    {
        new SortResolver('');
    }
}
