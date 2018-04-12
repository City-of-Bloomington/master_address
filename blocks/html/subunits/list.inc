<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array   $this->subunits
 * @param Address $this->address
 */
use Application\People\Person;
?>
<section>
    <header>
        <h2><?= $this->_(['subunit', 'subunits', count($this->subunits)]); ?></h2>
        <div class="tools">
        <?php
            if (isset($this->address) && parent::isAllowed('subunits', 'add')) {
                $h = $this->template->getHelper('buttonLink');
                echo $h->buttonLink(
                    parent::generateUri('subunits.add').'?address_id='.$this->address->id,
                    $this->_('subunit_add'),
                    'add'
                );
            }
        ?>
        </div>
    </header>
    <table>
        <thead>
            <tr><th><?= $this->_('type'); ?></th>
                <th><?= $this->_('id'); ?></th>
                <th><?= $this->_('status'); ?></th>
                <th><?= "{$this->_('latitude')},{$this->_('longitude')}"; ?></th>
                <th><?= "{$this->_('x')},{$this->_('y')}"; ?></th>
                <th><?= $this->_('USNG'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
			foreach ($this->subunits as $s) {
                $url = parent::generateUri('subunits.view', ['id'=>$s->id]);
				echo "
				<tr><td><a href=\"$url\">{$s->type_code}</a></td>
					<td><a href=\"$url\">{$s->identifier}</a></td>
					<td>{$s->status}</td>
					<td>{$s->latitude},{$s->longitude}</td>
					<td>{$s->state_plane_x},{$s->state_plane_y}</td>
					<td>{$s->usng}</td>
				</tr>
				";
			}
        ?>
        </tbody>
    </table>
</section>
