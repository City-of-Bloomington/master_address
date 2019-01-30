<?php
/**
 * @copyright 2009-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->plats
 */
?>
<section>
    <header>
        <h1><?= $this->_(['plat', 'plats', count($this->plats)]); ?></h1>
        <div class="tools">
        <?php
            $userCanEdit = parent::isAllowed('plats', 'update');
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
                $uri       = parent::generateUri('plats.view', ['id'=>$plat->id]);
				$name      = parent::escape($plat->name);
				$type      = parent::escape($plat->plat_type);
				$cabinet   = parent::escape($plat->cabinet);
				$envelope  = parent::escape($plat->envelope);
				$township  = parent::escape($plat->township_name);
				$startDate = $plat->start_date ? $plat->start_date->format(DATE_FORMAT) : '';
				$endDate   = $plat->end_date   ? $plat->end_date  ->format(DATE_FORMAT) : '';
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
</section>
