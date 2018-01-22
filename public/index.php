<?php
/**
 * @copyright 2015-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
/**
 * Grab a timestamp for calculating process time
 */
$startTime = microtime(1);

include '../bootstrap.inc';

$p = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = $ROUTES->match($p, $_SERVER);
if ($route) {
    if (isset($route->params['controller']) && isset($route->params['action'])) {
        $controller = $route->params['controller'];
        $action     = $route->params['action'];
        $c = new $controller();
        if (method_exists($c, $action)) {
            list($resource, $permission) = explode('.', $route->name);
            $role = isset($_SESSION['USER']) ? $_SESSION['USER']->role : 'Anonymous';
            if (   $ZEND_ACL->hasResource($resource)
                && $ZEND_ACL->isAllowed($role, $resource, $permission)) {
                if (!empty($route->params['id'])) {
                        $_GET['id'] = $route->params['id'];
                    $_REQUEST['id'] = $route->params['id'];
                }
                $view = $c->$action($route->params);
            }
            else {
                $view = new \Application\Views\ForbiddenView();
            }
        }
        else {
            $f = $ROUTES->getFailedRoute();
            $view = new \Application\Views\NotFoundView();
        }
    }
}
else {
    $f = $ROUTES->getFailedRoute();
    $view = new \Application\Views\NotFoundView();
}

echo $view->render();

if ($view->outputFormat === 'html') {
    # Calculate the process time
    $endTime = microtime(1);
    $processTime = $endTime - $startTime;
    echo "<!-- Process Time: $processTime -->";
}
