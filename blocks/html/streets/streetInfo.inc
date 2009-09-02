<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Street $this->street
 */
$street = $this->street;
$name = View::escape($street->getStreetName());
$status = View::escape($street->getStatus()->getDescription());
$town = View::escape($street->getTown()->getDescription());
$notes = View::escape($street->getNotes());
?>
<div class="interfaceBox" id="streetInfo">
	<h2>Street Information</h2>
	<table>
	<?php
		if (userIsAllowed('Street')) {
			$actionURL = BASE_URL.'/streets/actions.php';
			echo "
				<tr><th>Action</th>
					<td><select onchange=\"handleActionChoice(this);\">
							<option value=\"\">Action to Apply</option>
							<option>correct</option>
							<option>retire</option>
							<option>verify</option>
						</select>
					</td>
				</tr>
			";
		}
		echo "
		<tr><th>Name</th><td>$name</td></tr>
		<tr><th>Status</th><td>$status</td></tr>
		<tr><th>Town</th><td>$town</td></tr>
		<tr><th>Notes</th><td>$notes</td></tr>
		";
	?>
	</table>
</div>
<script type="text/javascript">
	function handleActionChoice(select) {
		document.location.href = '<?php echo BASE_URL; ?>/streets/actions.php?action=' +
									select.options[select.selectedIndex].value +
									';street_id=<?php echo $this->street->getId(); ?>';
	}
</script>
