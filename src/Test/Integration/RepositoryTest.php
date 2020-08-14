<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{
    protected static $repo;

    public static function setUpBeforeClass(): void
    {
        global $DI;
        self::$repo = $DI->get('Domain\Addresses\DataStorage\AddressesRepository');
    }

    public function testIdValidator()
    {
        foreach (self::$repo->townships() as $t) {
            $this->assertTrue(self::$repo->isValidId($t['id'], 'townships'));
        }
    }
}
