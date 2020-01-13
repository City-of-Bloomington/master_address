<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
use PHPUnit\Framework\TestCase;

class RoutesTest extends TestCase
{
    public function testRoutesHaveFunctions()
    {
        global $ROUTES, $DI;

        foreach ($ROUTES->getRoutes() as $r) {
            $controller = $r->values['controller'];
            $action     = $r->values['action'    ];
            $c = new $controller($DI);
            $this->assertTrue(method_exists($c, $action), "$controller is missing $action()\n");
        }
    }
}
