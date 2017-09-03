<?php
namespace HHIT\Web\Common\Request;

class LimitOffsetRequest implements LimitOffsetInterface
{

    /**
     * @var int
     */
    private $offset;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var SortableInterface
     */
    private $sortable;

    public function __construct($limit, $offset, SortableInterface $sortable = null)
    {
        if ($limit === null || $limit < 1) {
            throw new \InvalidArgumentException('Limit must be greater or equal to 1!');
        }
        if ($offset === null || $offset < 0) {
            throw new \InvalidArgumentException('Offset must be greater or equal to 0!');
        }

        $this->limit = intval($limit);
        $this->offset = intval($offset);
        $this->sortable = $sortable == null ? new SortableRequest() : $sortable;
    }

    /**
     * @inheritdoc
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @inheritdoc
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @inheritdoc
     */
    public function getSortable()
    {
        return $this->sortable;
    }
}
