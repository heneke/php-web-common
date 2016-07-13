<?php
namespace Heneke\Web\Common\Request;

interface SortInterface
{
    const DIR_ASC = 'ASC';
    const DIR_DESC = 'DESC';

    /**
     * Returns the property to sort on.
     *
     * @return string
     */
    public function getProperty();

    /**
     * Returns the direction.
     *
     * @return string
     */
    public function getDirection();
}