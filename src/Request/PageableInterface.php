<?php
namespace HHIT\Web\Common\Request;

interface PageableInterface
{

    /**
     * Return the offset of the underlying page
     *
     * @return mixed
     */
    public function getOffset();

    /**
     * Returns the 1-based page number
     *
     * @return int
     */
    public function getPageNumber();

    /**
     * Return the page size
     *
     * @return integer
     */
    public function getPageSize();

    /**
     * Returns the next pageable
     *
     * @return PageableInterface
     */
    public function next();

    /**
     * Returns the previous or first pageable
     *
     * @return PageableInterface
     */
    public function previousOrFirst();

    /**
     * Returns the sortable
     *
     * @return SortableInterface
     */
    public function getSortable();
}
