<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$queryType = isset($_GET['queryType']) ? $_GET['queryType'] : 'address';
$query = isset($_GET['query']) ? View::escape($_GET['query']) : '';
?>
<div class="interfaceBox">
<form method="get" action="<?php echo BASE_URL; ?>">
	<fieldset><legend>Search</legend>
		<input name="query" id="multiSearchForm_input" value="<?php echo $query; ?>" />
		<select name="queryType">
			<option value="address" <?php echo $queryType=='address' ? 'selected="selected"' : ''; ?>>Addresses</option>
			<option value="street" <?php echo $queryType=='street' ? 'selected="selected"' : ''; ?>>Streets</option>
		</select>
		<button type="submit" class="search">Search</button>
	</fieldset>
</form>
</div>
