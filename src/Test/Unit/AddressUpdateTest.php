<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use PHPUnit\Framework\TestCase;

use Test\DataStorage\TestAddressesRepository;

use Domain\Addresses\UseCases\Update\Command;
use Domain\Addresses\UseCases\Update\Request;
use Domain\Addresses\UseCases\Update\Response;

use Domain\Logs\Entities\ChangeLogEntry;

class AddressUpdateTest extends TestCase
{
    private const LOG_ID     = 1;
    private const ADDRESS_ID = 2;

    public function testResponseCreation()
    {
        $response = new Response(LOG_ID, ADDRESS_ID);
        $this->assertEquals(LOG_ID, $response->entry_id);
        $this->assertEquals(ADDRESS_ID, $response->address_id);
    }

    public function testSuccess()
    {
        $repo = new TestAddressesRepository();

        $update = new Command($repo);
        $req    = new Request(LOG_ID, ADDRESS_ID, ['address_type'=>'Test', 'jurisdiction_id'=>1]);

        $res = $update($req);

        $this->assertEquals(LOG_ID,     $res->entry_id,   'Update command did not set log entry id');
        $this->assertEquals(ADDRESS_ID, $res->address_id, 'Update command did not set address_id');
    }
}
