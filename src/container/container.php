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

$DI->params[ 'Domain\Towns\UseCases\Search\Search']['townsRepository'] = $DI->get('Domain\Towns\DataStorage\TownsRepository');
$DI->set(    'Domain\Towns\UseCases\Search\Search',
$DI->lazyNew('Domain\Towns\UseCases\Search\Search'));
