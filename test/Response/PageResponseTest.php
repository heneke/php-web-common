<?php
namespace Heneke\Web\Common\Response;

use Heneke\Web\Common\Request\PageableRequest;
use Heneke\Web\Common\Request\SortableRequest;
use Heneke\Web\Common\Request\SortInterface;
use Heneke\Web\Common\Request\SortRequest;

class PageResponseTest extends \PHPUnit_Framework_TestCase
{

    private function createArray($size, $value = 'a')
    {
        return array_fill(0, $size, $value);
    }

    /**
     * @test
     */
    public function content()
    {
        $p = new PageResponse($this->createArray(10), new PageableRequest(1, 10, new SortableRequest([new SortRequest('prop', SortInterface::DIR_DESC)])), 100);
        $this->assertNotNull($p->getContent());
        $this->assertCount(10, $p->getContent());
        $this->assertEquals(100, $p->getTotalElements());
        $this->assertNotNull($p->getPageable());
        $this->assertEquals(1, $p->getPageable()->getPageNumber());
        $this->assertEquals(10, $p->getPageable()->getPageSize());
        $this->assertNotNull($p->getPageable()->getSortable());
        $this->assertNotNull($p->getPageable()->getSortable()->getSorting());
        $this->assertCount(1, $p->getPageable()->getSortable()->getSorting());
        $s = $p->getPageable()->getSortable()->getSorting()[0];
        $this->assertEquals('prop', $s->getProperty());
        $this->assertEquals(SortInterface::DIR_DESC, $s->getDirection());
    }

    /**
     * @test
     * @dataProvider pagingDataProvider
     */
    public function paging(PageResponse $page, $hasNext, $nextPage, $hasPrevious, $previousPage, $totalPages)
    {
        $this->assertEquals($hasNext, $page->hasNextPageable());
        if ($hasNext) {
            $this->assertEquals($nextPage, $page->nextPageable()->getPageNumber());
        }
        $this->assertEquals($hasPrevious, $page->hasPreviousPageable());
        if ($hasPrevious) {
            $this->assertEquals($previousPage, $page->previousPageable()->getPageNumber());
        }
        $this->assertEquals($totalPages, $page->getTotalPages());
    }

    /**
     * @cover
     */
    public function pagingDataProvider()
    {
        return [
            [new PageResponse($this->createArray(10), new PageableRequest(1, 10), 100), true, 2, false, null, 10],
            [new PageResponse($this->createArray(10), new PageableRequest(2, 10), 100), true, 3, true, 1, 10],
            [new PageResponse($this->createArray(10), new PageableRequest(10, 10), 100), false, null, true, 9, 10],
            [new PageResponse($this->createArray(10), new PageableRequest(1, 10), 90), true, 2, false, null, 9],
            [new PageResponse($this->createArray(10), new PageableRequest(1, 10), 91), true, 2, false, null, 10],
        ];
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function noNext()
    {
        $p = new PageResponse($this->createArray(10), new PageableRequest(1, 10), 10);
        $p->nextPageable();
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function noPrevious()
    {
        $p = new PageResponse($this->createArray(10), new PageableRequest(1, 10), 10);
        $p->previousPageable();
    }

    /**
     * @test
     */
    public function intMax()
    {
        $p = new PageResponse($this->createArray(2), new PageableRequest(1, PHP_INT_MAX), PHP_INT_MAX);
        $this->assertEquals(1, $p->getTotalPages());
    }
}