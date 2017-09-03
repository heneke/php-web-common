<?php
namespace HHIT\Web\Common\Request;

interface SortableInterface
{

    /**
     * @return SortInterface[]
     */
    public function getSorting();
}
