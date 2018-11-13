<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<?php $lang = strtolower(substr(LOCALE, 0, 2)); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?= $lang; ?>" lang="<?= $lang; ?>">
<?php
	$this->_include('partials/head.inc');
?>
<body class="callback">
	<main>
        <div id="content-panel" class="panel">
            <?php
                include BLOSSOM.'/errorMessages.php';
                echo $this->includeBlocks();
            ?>
        </div>
	</main>
</body>
</html>
<?php
	$this->_include('partials/garbageCollection.inc');
?>
