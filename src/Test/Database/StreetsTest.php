<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Test\Database;

use PHPUnit\Framework\TestCase;
use Domain\Streets\Names\UseCases\Add\AddRequest as NameRequest;
use Domain\Streets\UseCases\Add\AddRequest as StreetRequest;
use Domain\Logs\Metadata as Log;

class StreetsTest extends TestCase
{
    const TYPE_STREET = 1;
    const PERSON_TEST = 1;

    protected static $DI;
    protected static $pdo;

    public static function setUpBeforeClass(): void
    {
        global $DI, $pdo;
        self::$DI  = $DI;
        self::$pdo = $pdo;

        self::$pdo->query('truncate table people       cascade');
        self::$pdo->query('truncate table streets      cascade');
        self::$pdo->query('truncate table street_types cascade');

        self::$pdo->query("insert into street_types values(1, 'ST', 'Street')");
        self::$pdo->query("insert into people (id, firstname, lastname, current) values(1, 'Test', 'Person', TRUE)");
    }

    public function testCreateName()
    {
        $add = self::$DI->get('Domain\Streets\Names\UseCases\Add\Add');
        $req = new NameRequest(['name' => 'Test', 'suffix_code_id' => self::TYPE_STREET]);
        $res = $add($req);
        $this->assertNotEmpty($res->id, "Failed to create a test name\n".self::responseError($res));

        $add = self::$DI->get('Domain\Streets\UseCases\Add\Add');
        $req = new StreetRequest(self::PERSON_TEST, new \DateTime(), [
                   'status'  => Log::STATUS_CURRENT,
                   'name_id' => $res->id
               ]);
        $res = $add($req);
        $this->assertNotEmpty($res->street_id, "Failed to create a test street\n".self::responseError($res));
    }

    public static function tearDownAfterClass(): void
    {
    }

    private static function responseError($response): string
    {
        return $response->errors[0] ?? '';
    }
}
