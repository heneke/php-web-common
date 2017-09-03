<?php
namespace HHIT\Web\Common\Response;

interface PageInterface
{

    /**
     * @return array
     */
    public function getContent();

    /**
     * @return PageableInterface
     */
    public function getPageable();

    /**
     * @return int
     */
    public function getTotalElements();

    /**
     * @return int
     */
    public function getTotalPages();

    /**
     * @return boolean
     */
    public function hasNextPageable();

    /**
     * @return boolean
     */
    public function hasPreviousPageable();

    /**
     * @return PageableInterface
     */
    public function nextPageable();

    /**
     * @return PageableInterface
     */
    public function previousPageable();
}