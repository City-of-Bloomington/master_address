<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use Aura\Di\ContainerBuilder;

$builder = new ContainerBuilder();
$DI = $builder->newInstance();

$conf = $DATABASES['default'];
try {
    $pdo = new PDO("$conf[driver]:dbname=$conf[dbname];host=$conf[host]", $conf['username'], $conf['password'], $conf['options']);
}
catch (\Exception $e) {
    die("Could not connect to database server\n");
}
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
    'Subunits', 'Locations', 'Reports', 'Places'
];
foreach ($repos as $t) {
    $DI->params[ "Application\\$t\\Pdo{$t}Repository"]["pdo"] = $pdo;
    $DI->set("Domain\\$t\\DataStorage\\{$t}Repository",
    $DI->lazyNew("Application\\$t\\Pdo{$t}Repository"));
}
$DI->params[ 'Application\Streets\PdoNamesRepository']['pdo'] = $pdo;
$DI->set(    'Domain\Streets\Names\DataStorage\NamesRepository',
$DI->lazyNew('Application\Streets\PdoNamesRepository'));


//---------------------------------------------------------
// Metadata providers
//---------------------------------------------------------
$contexts = ['Addresses', 'Locations', 'Places', 'Plats', 'Streets', 'Subdivisions', 'Subunits'];
foreach ($contexts as $t) {
    $DI->params[ "Domain\\$t\\Metadata"]['repository'] = $DI->lazyGet("Domain\\$t\\DataStorage\\{$t}Repository");
    $DI->set(    "Domain\\$t\\Metadata",
    $DI->lazyNew("Domain\\$t\\Metadata"));
}

//---------------------------------------------------------
// Services
//---------------------------------------------------------
$DI->params[ 'Domain\Auth\AuthenticationService']['repository'] = $DI->lazyGet('Domain\Users\DataStorage\UsersRepository');
$DI->params[ 'Domain\Auth\AuthenticationService']['config'    ] = $DIRECTORY_CONFIG;
$DI->set(    'Domain\Auth\AuthenticationService',
$DI->lazyNew('Domain\Auth\AuthenticationService'));

//---------------------------------------------------------
// Use Cases
//---------------------------------------------------------
// Addresses
$useCases = [
    'Add', 'ChangeLog', 'ChangeStatus', 'Correct', 'Info', 'Load', 'Parse',
    'Readdress', 'Renumber', 'Search', 'Verify'
];
foreach ($useCases as $a) {
    $DI->params[ "Domain\\Addresses\\UseCases\\$a\\$a"]['repository'] = $DI->lazyGet('Domain\Addresses\DataStorage\AddressesRepository');
    $DI->set(    "Domain\\Addresses\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Addresses\\UseCases\\$a\\$a"));
}
foreach (['Activate', 'Import', 'Update', 'Validate'] as $a) {
    $DI->params[ "Domain\Addresses\UseCases\\$a\Command"]['repository'] = $DI->lazyGet('Domain\Addresses\DataStorage\AddressesRepository');
    $DI->set(    "Domain\Addresses\UseCases\\$a\Command",
    $DI->lazyNew("Domain\Addresses\UseCases\\$a\Command"));
}

$DI->params['Domain\Addresses\UseCases\ChangeStatus\ChangeStatus']['subunitChange'] = $DI->lazyGet('Domain\Subunits\UseCases\ChangeStatus\ChangeStatus');

// Jurisdictions
foreach (['Info', 'Search', 'Update', 'Validate'] as $a) {
    $DI->params[ "Domain\\Jurisdictions\\UseCases\\$a\\$a"]["repository"] = $DI->lazyGet('Domain\Jurisdictions\DataStorage\JurisdictionsRepository');
    $DI->set(    "Domain\\Jurisdictions\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Jurisdictions\\UseCases\\$a\\$a"));
}

// Locations
foreach (['Find', 'Search'] as $a) {
    $DI->params[ "Domain\\Locations\\UseCases\\$a\\$a"]['repository'] = $DI->lazyGet('Domain\Locations\DataStorage\LocationsRepository');
    $DI->set(    "Domain\\Locations\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Locations\\UseCases\\$a\\$a"));
}
foreach (['Load', 'Update'] as $a) {
    $DI->params[ "Domain\\Locations\\Sanitation\\UseCases\\$a\\$a"]['repository'] = $DI->lazyGet('Domain\Locations\DataStorage\LocationsRepository');
    $DI->set(    "Domain\\Locations\\Sanitation\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Locations\\Sanitation\\UseCases\\$a\\$a"));
}

// People
foreach(['Info', 'Load', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\People\\UseCases\\$a\\$a"]['repository'] = $DI->lazyGet('Domain\People\DataStorage\PeopleRepository');
    $DI->set(    "Domain\\People\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\People\\UseCases\\$a\\$a"));
}

// Plats
foreach (['Info', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\Plats\\UseCases\\$a\\$a"]["repository"] = $DI->lazyGet('Domain\Plats\DataStorage\PlatsRepository');
    $DI->set(    "Domain\\Plats\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Plats\\UseCases\\$a\\$a"));
}

// Streets
foreach (['Add', 'Alias', 'ChangeStatus', 'Info',
          'Intersections', 'IntersectingStreets',
          'Load', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\Streets\\UseCases\\$a\\$a"]['repository'] = $DI->lazyGet('Domain\Streets\DataStorage\StreetsRepository');
    $DI->set(    "Domain\\Streets\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Streets\\UseCases\\$a\\$a"));
}
$DI->params[ 'Domain\Streets\UseCases\ChangeName\ChangeName']['repository' ] = $DI->lazyGet('Domain\Streets\DataStorage\StreetsRepository');
$DI->params[ 'Domain\Streets\UseCases\ChangeName\ChangeName']['addressRepo'] = $DI->lazyGet('Domain\Addresses\DataStorage\AddressesRepository');
$DI->set(    "Domain\Streets\UseCases\ChangeName\ChangeName",
$DI->lazyNew("Domain\Streets\UseCases\ChangeName\ChangeName"));


// Street Designations
foreach (['Load', 'Update'] as $a) {
    $DI->params[ "Domain\\Streets\\Designations\\UseCases\\$a\\$a"]['repository'] = $DI->lazyGet('Domain\Streets\DataStorage\StreetsRepository');
    $DI->set(    "Domain\\Streets\\Designations\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Streets\\Designations\\UseCases\\$a\\$a"));
}
$DI->params[ "Domain\Streets\Designations\UseCases\Reorder\Command"]['repository'] = $DI->lazyGet('Domain\Streets\DataStorage\StreetsRepository');
$DI->set(    "Domain\Streets\Designations\UseCases\Reorder\Command",
$DI->lazyNew("Domain\Streets\Designations\UseCases\Reorder\Command"));


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
foreach (['Add', 'ChangeStatus', 'Correct', 'Info', 'Verify'] as $a) {
    $DI->params[ "Domain\\Subunits\\UseCases\\$a\\$a"]["repository"] = $DI->lazyGet('Domain\Subunits\DataStorage\SubunitsRepository');
    $DI->set(    "Domain\\Subunits\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Subunits\\UseCases\\$a\\$a"));
}
foreach (['Activate', 'Import', 'Update'] as $a) {
    $DI->params[ "Domain\Subunits\UseCases\\$a\Command"]["repository"] = $DI->lazyGet('Domain\Subunits\DataStorage\SubunitsRepository');
    $DI->set(    "Domain\Subunits\UseCases\\$a\Command",
    $DI->lazyNew("Domain\Subunits\UseCases\\$a\Command"));
}

// Towns
foreach (['Info', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\Towns\\UseCases\\$a\\$a"]["repository"] = $DI->lazyGet('Domain\Towns\DataStorage\TownsRepository');
    $DI->set(    "Domain\\Towns\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Towns\\UseCases\\$a\\$a"));
}

// Townships
foreach (['Info', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\Townships\\UseCases\\$a\\$a"]["repository"] = $DI->lazyGet('Domain\Townships\DataStorage\TownshipsRepository');
    $DI->set(    "Domain\\Townships\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Townships\\UseCases\\$a\\$a"));
}

// Users
foreach (['Delete', 'Info', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\Users\\UseCases\\$a\\$a"]["repository"] = $DI->lazyGet('Domain\Users\DataStorage\UsersRepository');
    $DI->set(    "Domain\\Users\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Users\\UseCases\\$a\\$a"));
}
$DI->params['Domain\Users\UseCases\Update\Update']['auth'] = $DI->lazyGet('Domain\Auth\AuthenticationService');

// Zip Codes
$DI->params[ "Domain\ZipCodes\UseCases\Index\Command"]["repository"] = $DI->lazyGet('Domain\Addresses\DataStorage\AddressesRepository');
$DI->set(    "Domain\ZipCodes\UseCases\Index\Command",
$DI->lazyNew("Domain\ZipCodes\UseCases\Index\Command"));

// Reports
foreach (\Domain\Reports\Report::list() as $r) {
    $DI->params[ "Site\Reports\\$r\Report"]['pdo'] = $pdo;
    $DI->set(    "Site\Reports\\$r\Report",
    $DI->lazyNew("Site\Reports\\$r\Report"));
}

// Places
foreach (['Add', 'Info', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\Places\\Actions\\$a\\Command"]["repository"] = $DI->lazyGet('Domain\Places\DataStorage\PlacesRepository');
    $DI->set(    "Domain\\Places\\Actions\\$a\\Command",
    $DI->lazyNew("Domain\\Places\\Actions\\$a\\Command"));
}
