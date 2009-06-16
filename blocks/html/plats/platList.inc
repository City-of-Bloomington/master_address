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
?>
<div class="interfaceBox">
	<h1><?php
			if (userHasRole('Administrator')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/plats/addPlat.php\">Add</a>";
			}
		?>
		Plats
	</h1>
	<table>
		<tr><th></th>
			<th>Name</th>
			<th>Township</th>
			<th>Start Date</th>
			<th>End Date</th>
			<th>Type</th>
			<th>Cabinet</th>
			<th>Envelope</th>
		</tr>
		<?php
			foreach ($this->platList as $plat) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/plats/updatePlat.php');
					$url->plat_id = $plat->getPlat_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				$name = View::escape($plat->getName());
				$township = $plat->getTownship_id() ? View::escape($plat->getTownship()->getName()) : '';
				$cabinet = View::escape($plat->getCabinet());
				$envelope = View::escape($plat->getEnvelope());
				echo "
				<tr><td>$editButton</td>
					<td>$name</td>
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
	</ul>
</div>
