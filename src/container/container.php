<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
use Aura\Di\Container;
use Aura\Di\Factory;

$conf = $DATABASES['default'];
$pdo  = new PDO($conf['dsn'], $conf['username'], $conf['password'], $conf['options']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$platform = ucfirst($pdo->getAttribute(PDO::ATTR_DRIVER_NAME));
if ($platform == 'Pgsql' && !empty($conf['schema'])) {
    $pdo->exec("set search_path to $conf[schema]");
}


$DI = new Container(new Factory());

$DI->params[ 'Domain\Users\DataStorage\PdoUsersRepository']['pdo'] = $pdo;
$DI->set(    'Domain\Users\DataStorage\UsersRepository',
$DI->lazyNew('Domain\Users\DataStorage\PdoUsersRepository'));

$DI->params[ 'Domain\Auth\AuthenticationService']['usersRepository'] = $DI->get('Domain\Users\DataStorage\UsersRepository');
$DI->set(    'Domain\Auth\AuthenticationService',
$DI->lazyNew('Domain\Auth\AuthenticationService')
);

$DI->params[ 'Domain\Auth\AuthorizationService']['permission'] = require APPLICATION_HOME.'/access_control.inc';
$DI->set(    'Domain\Auth\AuthorizationService',
$DI->lazyNew('Domain\Auth\AuthorizationService')
);
