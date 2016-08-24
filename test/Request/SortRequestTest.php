<?php
namespace Heneke\Web\Common\Request;

class SortRequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function ctorWithoutDirection()
    {
        $prop = 'prop';
        $sort = new SortRequest($prop);
        $this->assertEquals($prop, $sort->getProperty());
        $this->assertEquals(SortInterface::DIR_ASC, $sort->getDirection());
    }

    /**
     * @test
     */
    public function ctorWithDirection()
    {
        $prop = 'prop';
        $sort = new SortRequest($prop, SortInterface::DIR_DESC);
        $this->assertEquals($prop, $sort->getProperty());
        $this->assertEquals(SortInterface::DIR_DESC, $sort->getDirection());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Property required
     */
    public function ctorWithouProperty()
    {
        new SortRequest('');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Direction 'invalid' is not supported
     */
    public function ctorWithUnsupportedDirection()
    {
        new SortRequest('prop', 'invalid');
    }

    /**
     * @test
     */
    public function fromStringPropOnly()
    {
        $prop = 'prop';
        $input = " {$prop}  ";
        $sort = SortRequest::fromString($input);
        $this->assertEquals($prop, $sort->getProperty());
        $this->assertEquals(SortInterface::DIR_ASC, $sort->getDirection());
    }

    /**
     * @test
     */
    public function fromStringPropAndDirection()
    {
        $prop = 'prop';
        $dir = 'DesC';
        $input = "   {$prop}   $dir  ";
        $sort = SortRequest::fromString($input);
        $this->assertEquals($prop, $sort->getProperty());
        $this->assertEquals(strtoupper($dir), $sort->getDirection());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Input required
     */
    public function fromStringNoInput()
    {
        SortRequest::fromString('');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Input is empty
     */
    public function fromStringEmpty()
    {
        SortRequest::fromString('    ');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Input is too large
     */
    public function fromStringTooLarge()
    {
        SortRequest::fromString('A B C');
    }

    /**
     * @test
     */
    public function json()
    {
        $this->assertEquals('{"direction":"ASC","property":"prop"}', json_encode(new SortRequest('prop', SortInterface::DIR_ASC)));
    }
}
