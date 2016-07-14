<?php
namespace Heneke\Web\Common\Request;

class SortableRequest implements SortableInterface
{

    /**
     * @var SortInterface[]
     */
    private $sorting;

    public function __construct(array $sorting = [])
    {
        $this->sorting = $sorting == null ? [] : $sorting;
    }

    /**
     * @inheritdoc
     */
    public function getSorting()
    {
        return $this->sorting;
    }
}
