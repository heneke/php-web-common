<?php
namespace Heneke\Web\Common\Request\Impl;

use Heneke\Web\Common\Request\SortInterface;

class SortImplTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function ctorWithoutDirection()
    {
        $prop = 'prop';
        $sort = new SortImpl($prop);
        $this->assertEquals($prop, $sort->getProperty());
        $this->assertEquals(SortInterface::DIR_ASC, $sort->getDirection());
    }

    /**
     * @test
     */
    public function ctorWithDirection()
    {
        $prop = 'prop';
        $sort = new SortImpl($prop, SortInterface::DIR_DESC);
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
        new SortImpl('');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Direction 'invalid' is not supported
     */
    public function ctorWithUnsupportedDirection()
    {
        new SortImpl('prop', 'invalid');
    }

    /**
     * @test
     */
    public function fromStringPropOnly()
    {
        $prop = 'prop';
        $input = " {$prop}  ";
        $sort = SortImpl::fromString($input);
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
        $sort = SortImpl::fromString($input);
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
        SortImpl::fromString('');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Input is empty
     */
    public function fromStringEmpty()
    {
        SortImpl::fromString('    ');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Input is too large
     */
    public function fromStringTooLarge()
    {
        SortImpl::fromString('A B C');
    }
}
