<?php
namespace Owr\Tests\Unit\App\Controller;

class PaginationTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider paginationProviderForGetPage
     */
    public function testGetPage($raw, $expected)
    {
        $pagination = $this->getPagination();
        $this->assertEquals($expected, $pagination->getPage($raw));
    }

    /**
     * @dataProvider paginationProviderForGetCount
     */
    public function testGetCountPerPage($raw, $expected)
    {
        $pagination = $this->getPagination();
        $this->assertEquals($expected, $pagination->getCountPerPage($raw));
    }

    /**
     * @return \Owr\App\Controller\PaginationTrait
     */
    private function getPagination()
    {
        return $this->getObjectForTrait(\Owr\App\Controller\PaginationTrait::class);
    }

    public function paginationProviderForGetPage()
    {
        return [
            [1, 1],
            [-1, 1],
            [1., 1],
            ['x', 1],
            [101, 101],
        ];
    }

    public function paginationProviderForGetCount()
    {
        return [
            [1, 1],
            [-1, 1],
            [1., 1],
            ['x', 1],
            [101, 100],
        ];
    }
}