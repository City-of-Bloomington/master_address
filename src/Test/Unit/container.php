<?php
/**
 * @copyright 2019-2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use Aura\Di\ContainerBuilder;

$builder = new ContainerBuilder();
$DI = $builder->newInstance();

//---------------------------------------------------------
// Metadata providers
//---------------------------------------------------------
$DI->params[ 'Domain\Addresses\Metadata']['repository'] = $DI->lazyNew('Test\DataStorage\TestAddressesRepository');
$DI->set(    'Domain\Addresses\Metadata',
$DI->lazyNew('Domain\Addresses\Metadata'));

$DI->params[ 'Domain\Subunits\Metadata']['repository'] = $DI->lazyNew('Test\DataStorage\TestSubunitsRepository');
$DI->set(    'Domain\Subunits\Metadata',
$DI->lazyNew('Domain\Subunits\Metadata'));


//---------------------------------------------------------
// Services
//---------------------------------------------------------
$DI->set(    'Domain\Auth\AuthenticationService',
$DI->lazyNew('Test\DataStorage\TestAuthenticationService'));

//---------------------------------------------------------
// Use Cases
//---------------------------------------------------------
// Addresses
$DI->params[ 'Domain\Addresses\UseCases\Update\Command']['repository'] = $DI->lazyNew('Test\DataStorage\TestAddressesRepository');
$DI->set(    'Domain\Addresses\UseCases\Update\Command',
$DI->lazyNew('Domain\Addresses\UseCases\Update\Command'));

// Subunits
$DI->params[ 'Domain\Subunits\UseCases\Update\Command']['repository'] = $DI->lazyNew('Test\DataStorage\TestSubunitsRepository');
$DI->set(    'Domain\Subunits\UseCases\Update\Command',
$DI->lazyNew('Domain\Subunits\UseCases\Update\Command'));
