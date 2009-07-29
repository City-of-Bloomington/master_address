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
	$url = new URL(BASE_URL.'/streets/addStreetName.php');
	$url->street_id = $this->street->getId();
	$addButton = "
		<div>
			<button type=\"button\" onclick=\"document.location.href='$url';\">
				Add Street Name
			</button>
		</div>
		";
}
?>
<div class="interfaceBox">
	<h1>Street Names</h1>
	<?php echo $addButton; ?>
	<table>
		<tr><th>Direction</th>
			<th>Street Name</th>
			<th>Street Type</th>
			<th>Post Direction</th>
			<th>Name Type</th>
			<th></th>
		</tr>
		<?php
			foreach ($this->streetNameList as $streetName) {
				$editButton = '';
				if (userIsAllowed('StreetName')) {
					$url = new URL(BASE_URL.'/streets/updateStreetName.php');
					$url->id = $streetName->getId();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				$name = View::escape($streetName->getStreet_name());
				echo "
				<tr><td>{$streetName->getDirection()->getCode()}</td>
					<td>$name</td>
					<td>{$streetName->getStreetType()->getDescription()}</td>
					<td>{$streetName->getPostDirection()->getDescription()}</td>
					<td>{$streetName->getStreetNameType()}</td>
					<td>$editButton</td>
				</tr>
				";
			}
		?>

	</table>
</div>