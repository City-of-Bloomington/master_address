<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use PHPUnit\Framework\TestCase;

use Domain\Locations\UseCases\Search\SearchRequest;

class LocationTest extends TestCase
{
    public function testEmptyRequest()
    {
        $r = new SearchRequest(null, null, null, null);
        $this->assertTrue($r->isEmpty(), 'Search request not declared as empty');
    }

    public function testEmptyRequestPagination()
    {
        $r = new SearchRequest(null, null, 10, 1);
        $this->assertTrue($r->isEmpty(), 'Pagination prevents SearchRequest from being empty');
    }

    public function testNotEmpty()
    {
        $r = new SearchRequest(['street_name'=>'test']);
        $this->assertFalse($r->isEmpty(), 'SearchRequest with something in it treated as empty');
    }
}
