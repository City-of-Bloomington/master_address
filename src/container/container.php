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

//---------------------------------------------------------
// Declare database repositories
//---------------------------------------------------------
$repos = [
    'Addresses', 'Jurisdictions', 'People', 'Plats',
    'Streets', 'Subdivisions', 'Towns', 'Townships', 'Users',
    'Subunits', 'Locations'
];
foreach ($repos as $t) {
    $DI->params[ "Domain\\$t\\DataStorage\\Pdo{$t}Repository"]["pdo"] = $pdo;
    $DI->set(    "Domain\\$t\\DataStorage\\{$t}Repository",
    $DI->lazyNew("Domain\\$t\\DataStorage\\Pdo{$t}Repository"));
}
$DI->params[ 'Domain\Streets\Names\DataStorage\PdoNamesRepository']['pdo'] = $pdo;
$DI->set(    'Domain\Streets\Names\DataStorage\NamesRepository',
$DI->lazyNew('Domain\Streets\Names\DataStorage\PdoNamesRepository'));


//---------------------------------------------------------
// Metadata providers
//---------------------------------------------------------
$contexts = ['Addresses', 'Plats', 'Streets', 'Subdivisions'];
foreach ($contexts as $t) {
    $DI->params[ "Domain\\$t\\Metadata"]['repository'] = $DI->get("Domain\\$t\\DataStorage\\{$t}Repository");
    $DI->set(    "Domain\\$t\\Metadata",
    $DI->lazyNew("Domain\\$t\\Metadata"));
}

//---------------------------------------------------------
// Services
//---------------------------------------------------------
$DI->params[ 'Domain\Auth\AuthenticationService']['repository'] = $DI->get('Domain\Users\DataStorage\UsersRepository');
$DI->params[ 'Domain\Auth\AuthenticationService']['config'    ] = $AUTHENTICATION_METHODS;
$DI->set(    'Domain\Auth\AuthenticationService',
$DI->lazyNew('Domain\Auth\AuthenticationService'));

$DI->params[ 'Domain\Addresses\UseCases\Parse\Parse']['repository'] = $DI->get('Domain\Addresses\DataStorage\AddressesRepository');
$DI->set(    'Domain\Addresses\UseCases\Parse\Parse',
$DI->lazyNew('Domain\Addresses\UseCases\Parse\Parse'));

//---------------------------------------------------------
// Use Cases
//---------------------------------------------------------
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

foreach (['Addresses', 'Streets', 'Subunits'] as $t) {
    foreach (['Load', 'Verify', 'Correct', 'Retire', 'Unretire'] as $a) {
        $DI->params[ "Domain\\$t\\UseCases\\$a\\$a"]["repository"] = $DI->get("Domain\\$t\\DataStorage\\{$t}Repository");
        $DI->set(    "Domain\\$t\\UseCases\\$a\\$a",
        $DI->lazyNew("Domain\\$t\\UseCases\\$a\\$a"));
    }
}
$DI->params['Domain\Addresses\UseCases\Retire\Retire']['subunitRetire'] = $DI->get('Domain\Subunits\UseCases\Retire\Retire');

$DI->params[ 'Domain\Streets\Names\UseCases\Search\Search']['repository'] = $DI->get('Domain\Streets\Names\DataStorage\NamesRepository');
$DI->set(    'Domain\Streets\Names\UseCases\Search\Search',
$DI->lazyNew('Domain\Streets\Names\UseCases\Search\Search'));
