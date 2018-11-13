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
$pdo  = new PDO("$conf[driver]:dbname=$conf[dbname];host=$conf[host]", $conf['username'], $conf['password'], $conf['options']);
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
$contexts = ['Addresses', 'Locations', 'Plats', 'Streets', 'Subdivisions', 'Subunits'];
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

//---------------------------------------------------------
// Use Cases
//---------------------------------------------------------
// Addresses
$useCases = [
    'Add', 'ChangeLog', 'Correct', 'Info', 'Load', 'Parse',
    'Renumber', 'Retire', 'Search', 'Unretire', 'Verify'
];
foreach ($useCases as $a) {
    $DI->params[ "Domain\\Addresses\\UseCases\\$a\\$a"]['repository'] = $DI->lazyGet('Domain\Addresses\DataStorage\AddressesRepository');
    $DI->set(    "Domain\\Addresses\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Addresses\\UseCases\\$a\\$a"));
}
$DI->params['Domain\Addresses\UseCases\Retire\Retire']['subunitRetire']  = $DI->lazyGet('Domain\Subunits\UseCases\Retire\Retire');

// Jurisdictions
foreach (['Info', 'Search', 'Update', 'Validate'] as $a) {
    $DI->params[ "Domain\\Jurisdictions\\UseCases\\$a\\$a"]["repository"] = $DI->lazyGet('Domain\Jurisdictions\DataStorage\JurisdictionsRepository');
    $DI->set(    "Domain\\Jurisdictions\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Jurisdictions\\UseCases\\$a\\$a"));
}

// Locations
foreach (['Find', 'Load'] as $a) {
    $DI->params[ "Domain\\Locations\\UseCases\\$a\\$a"]['repository'] = $DI->lazyGet('Domain\Locations\DataStorage\LocationsRepository');
    $DI->set(    "Domain\\Locations\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Locations\\UseCases\\$a\\$a"));
}

// People
foreach(['Info', 'Load', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\People\\UseCases\\$a\\$a"]['repository'] = $DI->lazyGet('Domain\People\DataStorage\PeopleRepository');
    $DI->set(    "Domain\\People\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\People\\UseCases\\$a\\$a"));
}

// Plats
foreach (['Info', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\Plats\\UseCases\\$a\\$a"]["repository"] = $DI->lazyGet("Domain\\Plats\\DataStorage\\PlatsRepository");
    $DI->set(    "Domain\\Plats\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Plats\\UseCases\\$a\\$a"));
}

// Streets
foreach (['Add', 'Alias', 'ChangeName', 'ChangeStatus', 'Info', 'Load', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\Streets\\UseCases\\$a\\$a"]['repository'] = $DI->lazyGet('Domain\Streets\DataStorage\StreetsRepository');
    $DI->set(    "Domain\\Streets\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Streets\\UseCases\\$a\\$a"));
}

// Street Designations
foreach (['Load', 'Update'] as $a) {
    $DI->params[ "Domain\\Streets\\Designations\\UseCases\\$a\\$a"]['repository'] = $DI->lazyGet('Domain\Streets\DataStorage\StreetsRepository');
    $DI->set(    "Domain\\Streets\\Designations\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Streets\\Designations\\UseCases\\$a\\$a"));
}

// Street Names
foreach (['Add', 'Info', 'Search', 'Correct', 'Load'] as $a) {
    $DI->params[ "Domain\\Streets\\Names\\UseCases\\$a\\$a"]['repository'] = $DI->lazyGet('Domain\Streets\Names\DataStorage\NamesRepository');
    $DI->set(    "Domain\\Streets\\Names\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Streets\\Names\\UseCases\\$a\\$a"));
}

// Subdivisions
foreach (['Info', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\Subdivisions\\UseCases\\$a\\$a"]['repository'] = $DI->lazyGet('Domain\Subdivisions\DataStorage\SubdivisionsRepository');
    $DI->set(    "Domain\\Subdivisions\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Subdivisions\\UseCases\\$a\\$a"));
}

// Subunits
foreach (['Add', 'Correct', 'Info', 'Retire', 'Unretire', 'Verify'] as $a) {
    $DI->params[ "Domain\\Subunits\\UseCases\\$a\\$a"]["repository"] = $DI->lazyGet("Domain\\Subunits\\DataStorage\\SubunitsRepository");
    $DI->set(    "Domain\\Subunits\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Subunits\\UseCases\\$a\\$a"));
}

// Towns
foreach (['Info', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\Towns\\UseCases\\$a\\$a"]["repository"] = $DI->lazyGet("Domain\\Towns\\DataStorage\\TownsRepository");
    $DI->set(    "Domain\\Towns\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Towns\\UseCases\\$a\\$a"));
}

// Townships
foreach (['Info', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\Townships\\UseCases\\$a\\$a"]["repository"] = $DI->lazyGet("Domain\\Townships\\DataStorage\\TownshipsRepository");
    $DI->set(    "Domain\\Townships\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Townships\\UseCases\\$a\\$a"));
}

foreach (['Delete', 'Info', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\Users\\UseCases\\$a\\$a"]["repository"] = $DI->lazyGet("Domain\\Users\\DataStorage\\UsersRepository");
    $DI->set(    "Domain\\Users\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Users\\UseCases\\$a\\$a"));
}
