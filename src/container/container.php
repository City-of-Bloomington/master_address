<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
use Aura\Di\Container;
use Aura\Di\Factory;

$DI = new Container(new Factory());

$conf = $DATABASES['default'];
$pdo  = new PDO($conf['dsn'], $conf['username'], $conf['password'], $conf['options']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$platform = ucfirst($pdo->getAttribute(PDO::ATTR_DRIVER_NAME));
if ($platform == 'Pgsql' && !empty($conf['schema'])) {
    $pdo->exec("set search_path to $conf[schema]");
}

$repos = ['Users', 'Towns', 'Townships', 'Jurisdictions', 'People', 'Plats', 'Subdivisions'];
foreach ($repos as $t) {
    $DI->params[ "Domain\\$t\\DataStorage\\Pdo{$t}Repository"]["pdo"] = $pdo;
    $DI->set(    "Domain\\$t\\DataStorage\\{$t}Repository",
    $DI->lazyNew("Domain\\$t\\DataStorage\\Pdo{$t}Repository"));
}

$DI->params[ 'Domain\Auth\AuthenticationService']['repository'] = $DI->get('Domain\Users\DataStorage\UsersRepository');
$DI->params[ 'Domain\Auth\AuthenticationService']['config'    ] = $AUTHENTICATION_METHODS;
$DI->set(    'Domain\Auth\AuthenticationService',
$DI->lazyNew('Domain\Auth\AuthenticationService'));

foreach ($repos as $t) {
    foreach (['Info', 'Search', 'Update'] as $a) {
        $DI->params[ "Domain\\$t\\UseCases\\$a\\$a"]["repository"] = $DI->get("Domain\\$t\\DataStorage\\{$t}Repository");
        $DI->set(    "Domain\\$t\\UseCases\\$a\\$a",
        $DI->lazyNew("Domain\\$t\\UseCases\\$a\\$a"));
    }
}

$DI->params[ 'Domain\Users\UseCases\Delete\Delete']['repository'] = $DI->get('Domain\Users\DataStorage\UsersRepository');
$DI->set(    'Domain\Users\UseCases\Delete\Delete',
$DI->lazyNew('Domain\Users\UseCases\Delete\Delete'));

$DI->params[ 'Domain\Plats\Metadata']['repository'] = $DI->get('Domain\Plats\DataStorage\PlatsRepository');
$DI->set(    'Domain\Plats\Metadata',
$DI->lazyNew('Domain\Plats\Metadata'));

$DI->params[ 'Domain\Subdivisions\Metadata']['repository'] = $DI->get('Domain\Subdivisions\DataStorage\SubdivisionsRepository');
$DI->set(    'Domain\Subdivisions\Metadata',
$DI->lazyNew('Domain\Subdivisions\Metadata'));
