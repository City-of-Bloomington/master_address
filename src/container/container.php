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

$DI->params[ 'Domain\Towns\UseCases\Info\Info']['repository'] = $DI->get('Domain\Towns\DataStorage\TownsRepository');
$DI->set(    'Domain\Towns\UseCases\Info\Info',
$DI->lazyNew('Domain\Towns\UseCases\Info\Info'));
$DI->params[ 'Domain\Towns\UseCases\Search\Search']['repository'] = $DI->get('Domain\Towns\DataStorage\TownsRepository');
$DI->set(    'Domain\Towns\UseCases\Search\Search',
$DI->lazyNew('Domain\Towns\UseCases\Search\Search'));
$DI->params[ 'Domain\Towns\UseCases\Update\Update']['repository'] = $DI->get('Domain\Towns\DataStorage\TownsRepository');
$DI->set(    'Domain\Towns\UseCases\Update\Update',
$DI->lazyNew('Domain\Towns\UseCases\Update\Update'));

$DI->params[ 'Domain\Townships\UseCases\Info\Info']['repository'] = $DI->get('Domain\Townships\DataStorage\TownshipsRepository');
$DI->set(    'Domain\Townships\UseCases\Info\Info',
$DI->lazyNew('Domain\Townships\UseCases\Info\Info'));
$DI->params[ 'Domain\Townships\UseCases\Search\Search']['repository'] = $DI->get('Domain\Townships\DataStorage\TownshipsRepository');
$DI->set(    'Domain\Townships\UseCases\Search\Search',
$DI->lazyNew('Domain\Townships\UseCases\Search\Search'));
$DI->params[ 'Domain\Townships\UseCases\Update\Update']['repository'] = $DI->get('Domain\Townships\DataStorage\TownshipsRepository');
$DI->set(    'Domain\Townships\UseCases\Update\Update',
$DI->lazyNew('Domain\Townships\UseCases\Update\Update'));
