<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Plat $this->plat
 */
$editButton = '';
if (userIsAllowed('Plat')) {
	$editButton = "
	<button type=\"button\" class=\"edit\" onclick=\"document.location.href='".BASE_URL."/plats/updatePlat.php?plat_id={$this->plat->getId()}';\">
		Edit
	</button>
	";
}
$name = View::escape($this->plat->getName());
$notes = View::escape($this->plat->getNotes());
echo "
<h1>$name $editButton</h1>
<table>
	<tr><th>Township</th>
		<td>{$this->plat->getTownship()}</td>
	</tr>
	<tr><th>Start Date</th>
		<td>{$this->plat->getStartDate('n/j/Y')}</td>
	</tr>
	<tr><th>End Date</th>
		<td>{$this->plat->getEndDate('n/j/Y')}</td>
	</tr>
	<tr><th>Type</th>
		<td>{$this->plat->getType()}</td>
	</tr>
	<tr><th>Cabinet</th>
		<td>{$this->plat->getCabinet()}</td>
	</tr>
	<tr><th>Envelope</th>
		<td>{$this->plat->getEnvelope()}</td>
	</tr>
</table>
<p>$notes</p>
";
