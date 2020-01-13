<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

use PHPUnit\Framework\TestCase;

class DirectoryTest extends TestCase
{
    protected static $auth;

    public static function setUpBeforeClass(): void
    {
        global $DI;
        self::$auth = $DI->get('Domain\Auth\AuthenticationService');
    }

    public function accountProvider()
    {
        global $pdo;

        $accounts = [];

        $sql = "select username, authentication_method from people
                where username is not null
                  and authentication_method is not null
                  and authentication_method != 'local'";
        $result = $pdo->query($sql);
        foreach ($result as $row) {
            $accounts[] = [$row['authentication_method'], $row['username']];
        }

        return $accounts;
    }

	/**
	 * @dataProvider accountProvider
	 */
    public function testDirectoryConnection(string $method, string $username)
    {
        $o = self::$auth->externalIdentify($method, $username);
        $this->assertEquals($username, $o->username);
        #$this->assertEquals('nonexistent', $o->username);

    }
}
