<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Street $this->street
 */
use Application\People\Person;
$streetNames = $this->street->getStreetNames();
?>
<section>
    <header>
        <h2><?= $this->_(['streetName', 'streetNames', count($streetNames)]); ?></h2>
    </header>
	<table>
		<thead>
			<tr>
				<th><?= $this->_('type'     ); ?></th>
				<th><?= $this->_('name'     ); ?></th>
				<th><?= $this->_('startDate'); ?></th>
				<th><?= $this->_('endDate'  ); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($streetNames as $streetName) {
                $startDate = $streetName->getStartDate();
                $endDate   = $streetName->getEndDate();

				$name  = parent::escape($streetName->getName());
				$type  = parent::escape($streetName->getNameType()->getName());
				$start = $startDate ? $startDate->format(DATE_FORMAT) : '';
				$end   = $endDate   ? $endDate  ->format(DATE_FORMAT) : '';

				echo "
				<tr>
					<td>$type</td>
					<td>$name</td>
					<td>$start</td>
					<td>$end</td>
					<td></td>
                </tr>
				";
			}
		?>
		</tbody>
	</table>
</section>
