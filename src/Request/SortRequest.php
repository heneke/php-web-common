<?php
namespace Heneke\Web\Common\Request;

class SortRequest# implements SortInterface
{

    /**
     * @var string
     */
    private $property;

    /**
     * @var string
     */
    private $direction;

    private static $allowedDirections = [SortInterface::DIR_ASC, SortInterface::DIR_DESC];

    public function __construct($property, $direction = null)
    {
        if (!$property) {
            throw new \InvalidArgumentException('Property required!');
        }
        $this->property = $property;
        if ($direction) {
            if (!in_array($direction, self::$allowedDirections)) {
                throw new \InvalidArgumentException("Direction '{$direction}' is not supported!");
            }
            $this->direction = $direction;
        } else {
            $this->direction = SortInterface::DIR_ASC;
        }
    }

    /**
     * Converts the input string
     *
     * @param $input string the string, e.g. the request parameter value
     * @return SortRequest
     */
    public static function fromString($input)
    {
        if (!$input) {
            throw new \InvalidArgumentException('Input required!');
        }
        $tokens = array_values(array_filter(array_map('trim', explode(' ', $input)), function ($e) {
            return $e ? true : false;
        }));
        if (empty($tokens)) {
            throw new \InvalidArgumentException('Input is empty!');
        } else if (count($tokens) > 2) {
            throw new \InvalidArgumentException('Input is too large!');
        } else if (count($tokens) == 1) {
            return new SortRequest($tokens[0]);
        } else {
            return new SortRequest($tokens[0], strtoupper($tokens[1]));
        }
    }

    /**
     * @inheritdoc
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @inheritdoc
     */
    public function getDirection()
    {
        return $this->direction;
    }
}

