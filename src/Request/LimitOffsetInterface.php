<?php
namespace Heneke\Web\Common\Request;

interface LimitOffsetInterface
{

    /**
     * Return the requested limit
     *
     * @return int
     */
    public function getLimit();

    /**
     * Returns the requested offset
     *
     * @return int
     */
    public function getOffset();

    /**
     * @return SortInterface[]
     */
    public function getSorting();
}
