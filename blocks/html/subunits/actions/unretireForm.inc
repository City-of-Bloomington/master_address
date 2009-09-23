<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W Sibo <sibow@bloomington.in.gov>
 * @param Subunit $this->subunit
 */
?>
<h1>Retire Subunit</h1>
<form method="post" action="<?php echo BASE_URL; ?>/subunits/actions.php"
  onsubmit="FRAMEWORK.getChangeLog(this,'unretireSubunitForm'); return false;">
	<fieldset>
		<input type="hidden" name="action" value="unretire" />
		<input type="hidden" name="subunit_id" value="<?php echo $this->subunit->getId(); ?>" />

		<div>
		I, <?php echo View::escape($_SESSION['USER']->getFullname()); ?>,
		verify that this subunit is to be unretired and set to current status.
		</div>

		<button type="submit" class="save">Save</button>
	</fieldset>

	<?php include APPLICATION_HOME.'/blocks/html/subunits/partials/subunitData.inc'; ?>

		<?php
			$block = new Block('changeLogs/changeLogEntryFields.inc',
								array('action'=>'unretire','id'=>'unretireSubunitForm'));
			echo $block->render('html');
		?>

</form>
