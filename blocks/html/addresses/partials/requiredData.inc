<?php
/**
 * @copyright 2009-2013 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
$township = View::escape($this->address->getTownship());
$plat     = View::escape($this->address->getPlat());
$notes    = View::escape($this->address->getNotes());

$typeClass         = $this->address->getType()         ? '' : 'class="missing"';
$jurisdictionClass = $this->address->getJurisdiction() ? '' : 'class="missing"';
$sectionClass      = $this->address->getSection()      ? '' : 'class="missing"';
$townshipClass     = $township                         ? '' : 'class="missing"';
echo "
	<tr $typeClass>
		<th>Address Type</th>
		<td>{$this->address->getType()}</td>
	</tr>
	<tr $jurisdictionClass>
		<th>Jurisdiction</th>
		<td>{$this->address->getJurisdiction()->getDescription()}</td>
	</tr>
	<tr><th>Plat</th>
		<td>$plat</td>
	</tr>
	<tr><th>Lot Number</th>
		<td>{$this->address->getPlat_lot_number()}</td>
	</tr>
	<tr $townshipClass><th>Township</th>
		<td>$township</td>
	</tr>
	<tr $sectionClass><th>Section</td>
		<td>{$this->address->getSection()}</td>
	</tr>
	<tr><th>Quarter Section</td>
		<td>{$this->address->getQuarter_section()}</td>
	</tr>
";
