<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param PlatList $this->platList
 */
if (!count($this->platList)) {
	echo "<h2>No plats found</h2>";
}
$updateURL = new URL(BASE_URL.'/plats/updatePlat.php');
?>
<div class="interfaceBox">
	<h1><?php
			if (userIsAllowed('Plat')) {
				echo "
				<button type=\"button\" class=\"add\" onclick=\"document.location.href='$updateURL';\">
					Add
				</button>
				";
			}
		?>
		Plats
	</h1>
	<table>
		<thead>
			<tr><th></th>
				<th>Name</th>
				<th>Township</th>
				<th>Start Date</th>
				<th>End Date</th>
				<th>Type</th>
				<th>Cabinet</th>
				<th>Envelope</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->platList as $plat) {
				$editButton = '';
				if (userIsAllowed('Plat')) {
					$updateURL->plat_id = $plat->getPlat_id();
					$editButton = "
					<button type=\"button\" class=\"edit\" onclick=\"document.location.href='$updateURL';\">
						Edit
					</button>
					";
				}
				$name = View::escape($plat->getName());
				$township = $plat->getTownship_id() ? View::escape($plat->getTownship()->getName()) : '';
				$cabinet = View::escape($plat->getCabinet());
				$envelope = View::escape($plat->getEnvelope());
				echo "
				<tr><td>$editButton</td>
					<td><a href=\"{$plat->getURL()}\">$name</a></td>
					<td>$township</td>
					<td>{$plat->getEffective_start_date('n/j/Y')}</td>
					<td>{$plat->getEffective_end_date('n/j/Y')}</td>
					<td>{$plat->getType()}</td>
					<td>$cabinet</td>
					<td>$envelope</td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
</div>
