<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
use Aura\Di\Container;
use Aura\Di\Factory;

$DI = new Container(new Factory());
include APPLICATION_HOME.'/src/container/repositories.php';

$DI->params[ 'Domain\Auth\AuthenticationService']['usersRepository'] = $DI->get('Domain\Users\DataStorage\UsersRepository');
$DI->set(    'Domain\Auth\AuthenticationService',
$DI->lazyNew('Domain\Auth\AuthenticationService'));

foreach (['Towns', 'Townships', 'Jurisdictions'] as $t) {
    foreach (['Info', 'Search', 'Update'] as $a) {
        $DI->params[ "Domain\\$t\\UseCases\\$a\\$a"]["repository"] = $DI->get("Domain\\$t\\DataStorage\\{$t}Repository");
        $DI->set(    "Domain\\$t\\UseCases\\$a\\$a",
        $DI->lazyNew("Domain\\$t\\UseCases\\$a\\$a"));
    }
}
