<?php
namespace Owr\App\Controller;

use Slim\Http\Request;

/**
 * Trait for pagination parameters
 *
 * @package Owr\App\Controller
 */
trait PaginationTrait
{
    /**
     * Returns page number
     *
     * @param int $page
     * @return int
     */
    public function getPage($page)
    {
        $page = abs($page);
        $page = $page > 0 ? $page : 1;

        return $page;
    }

    /**
     * Returns count of items per page
     *
     * @param int $count
     * @return int
     */
    public function getCountPerPage($count)
    {
        $count = abs($count);
        $count = $count > 0   ? $count : 1;
        $count = $count < 100 ? $count : 100;

        return $count;
    }
}