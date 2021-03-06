<?php
namespace HHIT\Web\Common\Request;

class PageableRequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @dataProvider offsetDataProvider
     */
    public function offset($pageNumber, $pageSize, $expectedOffset)
    {
        $pr = new PageableRequest($pageNumber, $pageSize);
        $this->assertEquals($pageNumber, $pr->getPageNumber());
        $this->assertEquals($pageSize, $pr->getPageSize());
        $this->assertEquals($expectedOffset, $pr->getOffset());
        $this->assertNotNull($pr->next());
        $this->assertEquals($pageNumber + 1, $pr->next()->getPageNumber());
        $this->assertEquals($pageSize, $pr->next()->getPageSize());
        $this->assertNotNull($pr->previousOrFirst());
        if ($pageNumber == 1) {
            $this->assertEquals(1, $pr->previousOrFirst()->getPageNumber());
        } else {
            $this->assertEquals($pageNumber - 1, $pr->previousOrFirst()->getPageNumber());
        }
        $this->assertEquals($pageSize, $pr->previousOrFirst()->getPageSize());
    }

    public function offsetDataProvider()
    {
        return [
            [1, 20, 0],
            [2, 20, 20],
            [2, 10, 10],
            [10, 1, 9],
        ];
    }

    /**
     * @test
     */
    public function json()
    {
        $this->assertEquals('{"pageNumber":1,"pageSize":10,"sortable":{"sorting":[]}}', json_encode(new PageableRequest(1, 10)));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Page number
     */
    public function invalidPage0()
    {
        new PageableRequest(0, 10);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Page size
     */
    public function invalidSize0()
    {
        new PageableRequest(1, 0);
    }
}
