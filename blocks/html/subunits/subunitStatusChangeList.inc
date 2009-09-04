<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param SubunitStatusChangeList $this->subunitStatusChangeList
 */
?>
<div class="interfaceBox">
	<h1>Status History</h1>
	<table>
		<tr>
			<th>Status</th>
			<th>Start Date</th>
			<th>End Date</th>
		</tr>
		<?php
			foreach ($this->subunitStatusChangeList as $subunitStatusChange) {
				echo "
				<tr><td>{$subunitStatusChange->getStatus()}</td>
					<td>{$subunitStatusChange->getStart_date('n/j/Y')}</td>
					<td>{$subunitStatusChange->getEnd_date('n/j/Y')}</td>
				</tr>
				";
			}
		?>
	</table>
</div>