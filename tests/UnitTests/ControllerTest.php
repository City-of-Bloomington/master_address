<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    public function controllerProvider()
    {
        return [
            ['\Application\People\LoginController',      'login', '\Application\People\Views\LoginView',           []],
            ['\Application\People\Controller',           'index', '\Application\People\Views\ListView',            []],
            ['\Application\People\UsersController',      'index', '\Application\People\Views\UserListView',        []],
            ['\Application\Plats\Controller',            'index', '\Application\Plats\Views\SearchView',           []],
            ['\Application\Streets\Controller',          'index', '\Application\Streets\Views\SearchView',         []],
            ['\Application\Streets\NameTypesController', 'index', '\Application\Streets\Views\NameTypes\ListView', []],
            ['\Application\Streets\TypesController',     'index', '\Application\Streets\Views\Types\ListView',     []],
            ['\Application\Subdivisions\Controller',     'index', '\Application\Subdivisions\Views\SearchView',    []],
            ['\Application\Subunits\TypesController',    'index', '\Application\Views\Generic\ListView',           []],
            ['\Application\Locations\TypesController',   'index', '\Application\Views\Generic\ListView',           []],
            ['\Application\Locations\PurposesController','index', '\Application\Views\Generic\ListView',           []],
            ['\Application\Jurisdictions\Controller',    'index', '\Application\Views\Generic\ListView',           []],
            ['\Application\Towns\Controller',            'index', '\Application\Views\Generic\ListView',           []],
            ['\Application\Townships\Controller',        'index', '\Application\Views\Generic\ListView',           []]
        ];
    }

    /**
     * @dataProvider controllerProvider
     */
    public function testIndexControllers($controllerClass, $action, $viewClass, $requestParams)
    {
        $controller = new $controllerClass();
        $view = $controller->$action($requestParams);
        $this->assertInstanceOf($viewClass, $view);
    }
}
