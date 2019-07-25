<?php
/**
 * @copyright 2009-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.html GNU/AGPL, see LICENSE
 * @param array  $this->designations
 * @param array  $this->actions        Optional
 * @param int    $this->street_id      Optional
 * @param bool   $this->disableButtons Optional
 */
?>
<section>
    <header>
        <h2><?= $this->_(['designation', 'designations', count($this->designations)]); ?></h2>
        <div class="tools">
        <?php
            $h = $this->template->getHelper('buttonLink');

            if (!$this->disableButtons && $this->actions && $this->street_id) {
                foreach ($this->actions as $a) {
                    echo $h->buttonLink(
                        parent::generateUri("streets.$a", ['id'=>$this->street_id]),
                        $this->_($a),
                        'edit'
                    );
                }
            }
        ?>
        </div>
    </header>
	<table>
		<thead>
			<tr><th><?= $this->_('type'     ); ?></th>
				<th><?= $this->_('name'     ); ?></th>
				<th><?= $this->_('startDate'); ?></th>
				<th><?= $this->_('rank'     ); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php
            $userCanEdit = !$this->disableButtons && parent::isAllowed('designations', 'update');
            $button      = '';

			foreach ($this->designations as $d) {
                $name  = parent::escape($d);
				$type  = parent::escape($d->type);
                $start = $d->start_date ? $d->start_date->format(DATE_FORMAT) : '';
                $rank  = $d->rank;

				if ($userCanEdit && $type != 'STREET') {
                    $button = $h->buttonLink(
                        parent::generateUri('designations.update', ['id'=>$d->id]),
                        $this->_('update'),
                        'edit'
                    );
                }

				echo "
				<tr><td>$type</td>
					<td>$name</td>
					<td>$start</td>
                    <td>$rank</td>
					<td>$button</td>
                </tr>
				";
			}
		?>
		</tbody>
	</table>
</section>
