<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
?>
<!DOCTYPE html>
<?php $lang = strtolower(substr(LOCALE, 0, 2)); ?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?= $lang; ?>">
<?php
	$this->_include('partials/head.inc');
?>
<body class="callback">
	<main>
        <div id="content-panel" class="panel">
            <?php
                include APPLICATION_HOME.'/templates/errorMessages.inc';
                echo $this->includeBlocks();
            ?>
        </div>
	</main>
</body>
</html>
<?php
	$this->_include('partials/garbageCollection.inc');
?>
