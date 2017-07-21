<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->plats
 */
use Application\Models\Person;

if (!count($this->plats)) {
    if (isset($_GET['name'])) {
        echo sprintf($this->_('no_results', 'messages'), $this->_(['plat', 'plats', 2]));
    }
}
else {
?>
<section>
    <header>
        <h1><?= $this->_(['plat', 'plats', count($this->plats)]); ?></h1>
        <div class="tools">
        <?php
            $userCanEdit = Person::isAllowed('plats', 'update');
            if ($userCanEdit) {
                $helper = $this->template->getHelper('buttonLink');
                echo $helper->buttonLink(
                    self::generateUri('plats.update'),
                    $this->_('plat_add'),
                    'add'
                );
            }
        ?>
        </div>
    </header>
	<table>
		<thead>
			<tr><th><?= $this->_('name'     ); ?></th>
				<th><?= $this->_('township' ); ?></th>
				<th><?= $this->_('startDate'); ?></th>
				<th><?= $this->_('endDate'  ); ?></th>
				<th><?= $this->_('type'     ); ?></th>
				<th><?= $this->_('cabinet'  ); ?></th>
				<th><?= $this->_('envelope' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php

			foreach ($this->plats as $plat) {
				$uri       = self::generateUri('plats.view', ['id'=>$plat->getId()]);
				$name      = parent::escape($plat->getName());
				$type      = parent::escape($plat->getType());
				$cabinet   = parent::escape($plat->getCabinet());
				$envelope  = parent::escape($plat->getEnvelope());
				$township  = $plat->getTownship_id() ? parent::escape($plat->getTownship()->getName()) : '';
				$startDate = $plat->getStartDate() ? $plat->getStartDate()->format(DATE_FORMAT) : '';
				$endDate   = $plat->getEndDate  () ? $plat->getEndDate  ()->format(DATE_FORMAT) : '';
				echo "
				<tr><td><a href=\"$uri\">$name</a></td>
					<td>$township</td>
					<td>$startDate</td>
					<td>$endDate</td>
					<td>$type</td>
					<td>$cabinet</td>
					<td>$envelope</td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
</div>
<?php
}
