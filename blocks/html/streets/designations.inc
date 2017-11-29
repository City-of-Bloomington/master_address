<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Street $this->street
 */
use Application\People\Person;
$designations = $this->street->getDesignations();

$userCanEdit = Person::isAllowed('streetDesignations', 'update');
?>
<section>
    <header>
        <h2><?= $this->_(['streetDesignation', 'streetDesignations', count($designations)]); ?></h2>
        <div class="tools">
        <?php
            if ($userCanEdit) {
                $helper = $this->template->getHelper('buttonLink');
                echo $helper->buttonLink(
                    parent::generateUri('streetDesignations.update').'?street_id='.$this->street->getId(),
                    $this->_('streetDesignations_add'),
                    'add'
                );
            }
        ?>
        </div>
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
			foreach ($designations as $designation) {
                $startDate = $designation->getStartDate();
                $endDate   = $designation->getEndDate();

				$name  = parent::escape($designation->getName());
				$type  = parent::escape($designation->getType()['name']);
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
