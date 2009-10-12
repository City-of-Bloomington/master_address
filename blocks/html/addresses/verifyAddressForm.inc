<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Verify Address</h1>
<form method="get" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Street Address</legend>
		<?php
			include APPLICATION_HOME.'/blocks/html/addresses/partials/advancedSearchFields.inc';
		?>
		<button type="submit">Verify</button>
	</fieldset>
</form>
