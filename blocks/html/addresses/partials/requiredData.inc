<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
$township = $this->address->getTownship()
			? View::escape($this->address->getTownship()->getName())
			: '';
$plat = $this->address->getPlat()
		? View::escape($this->address->getPlat()->getName())
		: '';
$notes = View::escape($this->address->getNotes());
echo "
	<tr><th>Address Type</th>
		<td>{$this->address->getType()}</td>
	</tr>
	<tr><th>Jurisdiction</th>
		<td>{$this->address->getJurisdiction()->getDescription()}</td>
	</tr>
	<tr><th>Plat</th>
		<td>$plat</td>
	</tr>
	<tr><th>Lot Number</th>
		<td>{$this->address->getPlat_lot_number()}</td>
	</tr>
	<tr><th>Township</th>
		<td>$township</td>
	</tr>
	<tr><th>Section</td>
		<td>{$this->address->getSection()}</td>
	</tr>
	<tr><th>Quarter Section</td>
		<td>{$this->address->getQuarter_section()}</td>
	</tr>
";