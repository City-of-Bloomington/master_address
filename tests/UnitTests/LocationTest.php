<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
use PHPUnit\Framework\TestCase;

use Domain\Locations\Entities\Location;
use Domain\Locations\UseCases\Validate\Validate;

class LocationTest extends TestCase
{
    public function testValidation()
    {
        $location = new Location();
        $validate = new Validate();
        $res = $validate($location);
        $this->assertEquals(1, count($res->errors));
    }
}
