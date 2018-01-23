<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);

$conf = $DATABASES['default'];
$pdo  = new PDO($conf['dsn'], $conf['username'], $conf['password'], $conf['options']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$platform = ucfirst($pdo->getAttribute(PDO::ATTR_DRIVER_NAME));
if ($platform == 'Pgsql' && !empty($conf['schema'])) {
    $pdo->exec("set search_path to $conf[schema]");
}

$DI->params[ 'Domain\Users\DataStorage\PdoUsersRepository']['pdo'] = $pdo;
$DI->set(    'Domain\Users\DataStorage\UsersRepository',
$DI->lazyNew('Domain\Users\DataStorage\PdoUsersRepository'));

$DI->params[ 'Domain\Towns\DataStorage\PdoTownsRepository']['pdo'] = $pdo;
$DI->set(    'Domain\Towns\DataStorage\TownsRepository',
$DI->lazyNew('Domain\Towns\DataStorage\PdoTownsRepository'));

$DI->params[ 'Domain\Townships\DataStorage\PdoTownshipsRepository']['pdo'] = $pdo;
$DI->set(    'Domain\Townships\DataStorage\TownshipsRepository',
$DI->lazyNew('Domain\Townships\DataStorage\PdoTownshipsRepository'));
