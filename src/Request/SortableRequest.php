<?php
namespace HHIT\Web\Common\Request;

class SortableRequest implements SortableInterface, \JsonSerializable
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

    public function jsonSerialize()
    {
        return ['sorting' => $this->sorting];
    }
}
