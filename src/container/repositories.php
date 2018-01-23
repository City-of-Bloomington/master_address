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

foreach (['Users', 'Towns', 'Townships', 'Jurisdictions'] as $t) {
    $DI->params[ "Domain\\$t\\DataStorage\\Pdo{$t}Repository"]["pdo"] = $pdo;
    $DI->set(    "Domain\\$t\\DataStorage\\{$t}Repository",
    $DI->lazyNew("Domain\\$t\\DataStorage\\Pdo{$t}Repository"));
}
