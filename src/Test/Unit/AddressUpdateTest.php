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
    private const USER_ID    = 3;

    public function testRequestCreation()
    {
        $req  = new Request(self::ADDRESS_ID, self::USER_ID);
        $this->assertEquals(self::ADDRESS_ID, $req->address_id);
        $this->assertEquals(self::USER_ID,    $req->user_id);
    }

    public function testResponseCreation()
    {
        $response = new Response(self::LOG_ID, self::ADDRESS_ID);
        $this->assertEquals(self::LOG_ID,     $response->entry_id);
        $this->assertEquals(self::ADDRESS_ID, $response->address_id);
    }

    public function testSuccess()
    {
        $repo = new TestAddressesRepository();

        $update = new Command($repo);
        $req    = new Request(self::ADDRESS_ID, self::USER_ID, ['address_type'=>'Test', 'jurisdiction_id'=>1]);

        $res = $update($req);

        $this->assertEquals(self::LOG_ID,     $res->entry_id,   'Update command did not set log entry id');
        $this->assertEquals(self::ADDRESS_ID, $res->address_id, 'Update command did not set address_id');
    }
}
