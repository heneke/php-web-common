<?php
namespace Heneke\Web\Common\Request\Impl;

use GuzzleHttp\Psr7\ServerRequest;

abstract class AbstractResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return ServerRequest
     */
    protected function createServerRequest($method, array $queryParams = null, array $postParams = null)
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
}
