<?php
namespace Heneke\Web\Common\Request\Impl;

use GuzzleHttp\Psr7\ServerRequest;

class SortImplResolverTest extends \PHPUnit_Framework_TestCase
{

    private $parameter = 'sort';

    /**
     * @var SortImplResolver
     */
    private $resolver;

    /**
     * @before
     */
    public function before()
    {
        $this->resolver = new SortImplResolver($this->parameter);
    }

    private function createServerRequest($method, array $queryParams = null, array $postParams = null)
    {
        $r = new ServerRequest($method, '/');
        if ($queryParams != null) {
            $r = $r->withQueryParams($queryParams);
        }
        if ($postParams != null) {
            $r = $r->withParsedBody($postParams);
        }
        return $r;
    }

    /**
     * @test
     * @expectedException \Heneke\Web\Common\Request\UnresolvableException
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
}
