<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$queryType = isset($_REQUEST['queryType']) ? $_REQUEST['queryType'] : 'address';
$query = isset($_REQUEST['query']) ? View::escape($_REQUEST['query']) : '';
?>
<div class="interfaceBox">
<form method="get" action="<?php echo BASE_URL; ?>">
	<fieldset><legend>Search</legend>
		<input name="query" id="multiSearchForm_input" value="<?php echo $query; ?>" />
		<select name="queryType" id="queryType">
			<option value="address" <?php echo $queryType=='address' ? 'selected="selected"' : ''; ?>>Addresses</option>
			<option value="street" <?php echo $queryType=='street' ? 'selected="selected"' : ''; ?>>Streets</option>
		</select>
		<button type="submit" class="search">Search</button>
		<button type="button" onclick="document.location.href=getAdvancedSearchURL();">Advanced Search</button>
	</fieldset>
</form>
</div>
<script type="text/javascript">
function getAdvancedSearchURL() {
	var select = document.getElementById('queryType');
	var queryType = select.options[select.selectedIndex].value;
	if (queryType == 'address') {
		queryType = 'addresses';
	}
	else {
		queryType = 'streets';
	}
	return '<?php echo BASE_URL; ?>/' + queryType;
}
</script>
