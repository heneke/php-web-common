<?php
namespace Heneke\Web\Common\Request\Impl;

use Heneke\Web\Common\Request\PageableInterface;
use Heneke\Web\Common\Request\SortInterface;

class PageableImpl implements PageableInterface
{

    /**
     * @var int
     */
    private $pageNumber;

    /**
     * @var int
     */
    private $pageSize;

    /**
     * @var SortInterface[]
     */
    private $sorting;

    public function __construct($pageNumber, $pageSize, array $sorting = [])
    {
        if ($pageNumber < 1) {
            throw new \InvalidArgumentException('Page number may not be lower than 1!');
        }
        if ($pageSize <= 0) {
            throw new \InvalidArgumentException('Page size must be greater than 0!');
        }
        $this->pageNumber = intval($pageNumber);
        $this->pageSize = intval($pageSize);
        $this->sorting = $sorting == null ? [] : $sorting;
    }

    /**
     * @inheritdoc
     */
    public function getOffset()
    {
        return ($this->pageNumber - 1) * $this->getPageSize();
    }

    /**
     * @inheritdoc
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * @inheritdoc
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        return new PageableImpl($this->pageNumber + 1, $this->pageSize);
    }

    /**
     * @inheritdoc
     */
    public function previousOrFirst()
    {
        $previousOrFirstPageNumber = $this->pageNumber == 1 ? 1 : $this->pageNumber - 1;
        return new PageableImpl($previousOrFirstPageNumber, $this->pageSize);
    }

    /**
     * @inheritdoc
     */
    public function getSorting()
    {
        return $this->sorting;
    }
}
