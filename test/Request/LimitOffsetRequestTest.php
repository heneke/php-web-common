<?php
namespace Heneke\Web\Common\Request;

class LimitOffsetRequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Limit must be greater or equal to 1
     */
    public function invalidLimitNull()
    {
        new LimitOffsetRequest(null, 10);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Limit must be greater or equal to 1
     */
    public function invalidLimit0()
    {
        new LimitOffsetRequest(0, 10);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Offset must be greater or equal to 0
     */
    public function invalidOffsetNull()
    {
        new LimitOffsetRequest(10, null);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Offset must be greater or equal to 0
     */
    public function invalidOffsetMin1()
    {
        new LimitOffsetRequest(10, -1);
    }

    /**
     * @test
     */
    public function values()
    {
        $lo = new LimitOffsetRequest(77, 88);
        $this->assertEquals(77, $lo->getLimit());
        $this->assertEquals(88, $lo->getOffset());
    }
}
