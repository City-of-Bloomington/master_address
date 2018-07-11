<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
use Aura\Di\ContainerBuilder;

$builder = new ContainerBuilder();
$DI = $builder->newInstance();

$conf = $DATABASES['default'];
$pdo  = new PDO($conf['dsn'], $conf['username'], $conf['password'], $conf['options']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$platform = ucfirst($pdo->getAttribute(PDO::ATTR_DRIVER_NAME));
if ($platform == 'Pgsql' && !empty($conf['schema'])) {
    $pdo->exec("set search_path=$conf[schema],public");
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
$contexts = ['Addresses', 'Plats', 'Streets', 'Subdivisions', 'Subunits'];
foreach ($contexts as $t) {
    $DI->params[ "Domain\\$t\\Metadata"]['repository'] = $DI->lazyGet("Domain\\$t\\DataStorage\\{$t}Repository");
    $DI->set(    "Domain\\$t\\Metadata",
    $DI->lazyNew("Domain\\$t\\Metadata"));
}

//---------------------------------------------------------
// Services
//---------------------------------------------------------
$DI->params[ 'Domain\Auth\AuthenticationService']['repository'] = $DI->lazyGet('Domain\Users\DataStorage\UsersRepository');
$DI->params[ 'Domain\Auth\AuthenticationService']['config'    ] = $AUTHENTICATION_METHODS;
$DI->set(    'Domain\Auth\AuthenticationService',
$DI->lazyNew('Domain\Auth\AuthenticationService'));

$DI->params[ 'Domain\Addresses\UseCases\Parse\Parse']['repository'] = $DI->lazyGet('Domain\Addresses\DataStorage\AddressesRepository');
$DI->set(    'Domain\Addresses\UseCases\Parse\Parse',
$DI->lazyNew('Domain\Addresses\UseCases\Parse\Parse'));

//---------------------------------------------------------
// Use Cases
//---------------------------------------------------------
foreach ($repos as $t) {
    foreach (['Info', 'Search', 'Update'] as $a) {
        $DI->params[ "Domain\\$t\\UseCases\\$a\\$a"]["repository"] = $DI->lazyGet("Domain\\$t\\DataStorage\\{$t}Repository");
        $DI->set(    "Domain\\$t\\UseCases\\$a\\$a",
        $DI->lazyNew("Domain\\$t\\UseCases\\$a\\$a"));
    }
}
$DI->params[ 'Domain\People\UseCases\Load\Load']['repository'] = $DI->lazyGet('Domain\People\DataStorage\PeopleRepository');
$DI->set(    'Domain\People\UseCases\Load\Load',
$DI->lazyNew('Domain\People\UseCases\Load\Load'));

$DI->params[ 'Domain\Users\UseCases\Delete\Delete']['repository'] = $DI->lazyGet('Domain\Users\DataStorage\UsersRepository');
$DI->set(    'Domain\Users\UseCases\Delete\Delete',
$DI->lazyNew('Domain\Users\UseCases\Delete\Delete'));

foreach (['Addresses', 'Streets', 'Subunits'] as $t) {
    foreach (['Add', 'Verify', 'Correct', 'Retire', 'Unretire'] as $a) {
        $DI->params[ "Domain\\$t\\UseCases\\$a\\$a"]["repository"] = $DI->lazyGet("Domain\\$t\\DataStorage\\{$t}Repository");
        $DI->set(    "Domain\\$t\\UseCases\\$a\\$a",
        $DI->lazyNew("Domain\\$t\\UseCases\\$a\\$a"));
    }
}
$DI->params['Domain\Addresses\UseCases\Retire\Retire']['subunitRetire'] = $DI->lazyGet('Domain\Subunits\UseCases\Retire\Retire');

foreach (['Load', 'Alias'] as $a) {
    $DI->params[ "Domain\\Streets\\UseCases\\$a\\$a"]['repository'] = $DI->lazyGet('Domain\Streets\DataStorage\StreetsRepository');
    $DI->set(    "Domain\\Streets\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Streets\\UseCases\\$a\\$a"));
}

foreach (['Info', 'Search', 'Correct', 'Load'] as $a) {
    $DI->params[ "Domain\\Streets\\Names\\UseCases\\$a\\$a"]['repository'] = $DI->lazyGet('Domain\Streets\Names\DataStorage\NamesRepository');
    $DI->set(    "Domain\\Streets\\Names\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Streets\\Names\\UseCases\\$a\\$a"));
}
