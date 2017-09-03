<?php
namespace HHIT\Web\Common\Request;

class SortableRequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function json()
    {
        $this->assertEquals('{"sorting":[{"direction":"ASC","property":"prop"}]}', json_encode(new SortableRequest([new SortRequest('prop', SortInterface::DIR_ASC)])));
    }
}