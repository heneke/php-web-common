<?php
namespace Heneke\Web\Common\Request;

interface SortableInterface
{

    /**
     * @return SortInterface[]
     */
    public function getSorting();
}
