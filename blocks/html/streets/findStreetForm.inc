<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<form method="get" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Search Streets</legend>
		<label>
			Street Name
			<input name="streetName"
					value="<?php echo isset($_GET['streetName']) ? View::escape($_GET['streetName']) : ''; ?>" />
		</label>
		<button type="submit" class="search">Search</button>
	</fieldset>
</form>
<?php
	if (isset($_GET['streetName'])) {
		$streets = new StreetList(array('streetName'=>$_GET['streetName']));
		$results = new Block('streets/streetList.inc',array('streetList'=>$streets));
		echo $results->render('html');
	}
?>