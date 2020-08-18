<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use PHPUnit\Framework\TestCase;

use Test\DataStorage\TestSubunitsRepository;

use Domain\Subunits\UseCases\Update\Command;
use Domain\Subunits\UseCases\Update\Request;
use Domain\Subunits\UseCases\Update\Response;

use Domain\Logs\Entities\ChangeLogEntry;

class SubunitUpdateTest extends TestCase
{
    private const LOG_ID     = 1;
    private const SUBUNIT_ID = 2;
    private const USER_ID    = 3;

    protected static $container;

    public static function setUpBeforeClass(): void
    {
        global $DI;
        self::$container = $DI;
    }

    public function testRequestCreation()
    {
        $req  = new Request(self::SUBUNIT_ID, self::USER_ID);
        $this->assertEquals(self::SUBUNIT_ID, $req->subunit_id);
        $this->assertEquals(self::USER_ID,    $req->user_id);
    }

    public function testResponseCreation()
    {
        $response = new Response(self::LOG_ID, self::SUBUNIT_ID);
        $this->assertEquals(self::LOG_ID,     $response->entry_id);
        $this->assertEquals(self::SUBUNIT_ID, $response->subunit_id);
    }

    public function testSuccess()
    {
        $update = self::$container->get('Domain\Subunits\UseCases\Update\Command');
        $req    = new Request(self::SUBUNIT_ID, self::USER_ID, ['locationType_id'=>4, 'contact_id'=>self::USER_ID]);

        // The test repo will always succeed and return log_id in the response
        $res = $update($req);

        $this->assertEmpty($res->errors, 'Update command returned validation errors');

        $this->assertEquals(self::LOG_ID,     $res->entry_id,   'Update command did not set log entry id');
        $this->assertEquals(self::SUBUNIT_ID, $res->subunit_id, 'Update command did not set subunit_id');
    }
}
