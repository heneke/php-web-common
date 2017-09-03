<?php
namespace HHIT\Web\Common\Response;

use HHIT\Web\Common\Request\PageableInterface;
use HHIT\Web\Common\Request\SortInterface;
use HHIT\Web\Common\Request\PageableRequest;
use HHIT\Web\Common\Request\SortableRequest;
use HHIT\Web\Common\Request\SortRequest;

class PageResponse implements PageInterface
{

    /**
     * @var array
     */
    private $content;

    /**
     * @var PageableInterface
     */
    private $pageable;

    /**
     * @var int
     */
    private $totalElements;

    /**
     * @var int
     */
    private $totalPages;

    public function __construct(array $content, PageableInterface $pageable, $totalElements)
    {
        $this->content = $content;
        $this->pageable = new PageableRequest($pageable->getPageNumber(), $pageable->getPageSize(), new SortableRequest($this->convertSorting($pageable->getSortable()->getSorting())));
        $this->totalElements = $totalElements;
        if ($this->getPageable()->getPageSize() != PHP_INT_MAX) {
            $this->totalPages = ceil((float)$this->getTotalElements() / (float)$this->getPageable()->getPageSize());
        } else {
            $this->totalPages = 1;
        }
    }

    /**
     * @param SortInterface[] $sorting
     * @return SortRequest[]
     */
    private function convertSorting(array $sorting)
    {
        $sort = [];
        foreach ($sorting as $s) {
            $sort[] = new SortRequest($s->getProperty(), $s->getDirection());
        }
        return $sort;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getPageable()
    {
        return $this->pageable;
    }

    public function hasNextPageable()
    {
        return $this->getTotalElements() > (($this->getPageable()->getPageNumber() - 1) * $this->getPageable()->getPageSize()) + count($this->getContent());
    }

    public function hasPreviousPageable()
    {
        return $this->getPageable()->getPageNumber() > 1;
    }

    public function getTotalElements()
    {
        return $this->totalElements;
    }

    public function getTotalPages()
    {
        return $this->totalPages;
    }

    public function nextPageable()
    {
        if ($this->hasNextPageable()) {
            return $this->getPageable()->next();
        }
        throw new \LogicException('No next pageable available!');
    }

    public function previousPageable()
    {
        if ($this->hasPreviousPageable()) {
            return $this->getPageable()->previousOrFirst();
        }
        throw new \LogicException('No previous pageable available!');
    }
}
