<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
header('Content-type: application/javascript; charset=utf-8');

include __DIR__.'/../../../bootstrap.inc';
// This must be the full url to master address.  It's will be interpreted
// from the viewpoint of a client application hosted elsewhere.
?>
var ADDRESS_SERVICE = '<?= BASE_URL; ?>';
