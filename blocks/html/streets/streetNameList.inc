<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param StreetNameList $this->streetNameList
 * @param Street $this->street
 */
$addButton = '';
if (userIsAllowed('Street')) {
	$url = BASE_URL.'/streets/actions.php?street_id='.$this->street->getId();
	$addButton = "
		<div>
			<button type=\"button\" onclick=\"document.location.href='$url;action=alias';\">
				Add Alias
			</button>
			<button type=\"button\" onclick=\"document.location.href='$url;action=change';\">
				Change Name
			</button>
		</div>
		";
}
?>
<div class="interfaceBox">
	<h1>Street Names</h1>
	<?php echo $addButton; ?>
	<table>
		<tr>
			<th>Type</th>
			<th>Name</th>
			<th>Start</th>
			<th>End</th>
		</tr>
		<?php
			foreach ($this->streetNameList as $streetName) {
				$name = View::escape($streetName);
				echo "
				<tr>
					<td>{$streetName->getStreetNameType()}</td>
					<td>$name</td>
					<td>{$streetName->getStartDate('n/j/Y')}</td>
					<td>{$streetName->getEndDate('n/j/Y')}</td>
				</tr>
				";
			}
		?>

	</table>
</div>