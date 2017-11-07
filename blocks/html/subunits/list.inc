<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array   $this->subunits
 * @param Address $this->address
 */
use Application\Models\Person;
?>
<section>
    <header>
        <h2><?= $this->_(['subunit', 'subunits', count($this->subunits)]); ?></h2>
        <div class="tools">
        <?php
            if (isset($this->address) && Person::isAllowed('subunits', 'update')) {
                $helper = $this->template->getHelper('buttonLink');
                echo $helper->buttonLink(
                    parent::generateUri('subunits.update').'?address_id='.$this->address->getId(),
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
                $url    = parent::generateUri('subunits.view', ['id'=>$s->getId()]);
                $type   = parent::escape($s->getType());
				$number = parent::escape($s->getIdentifier());
				$status = parent::escape($s->getStatus());
				echo "
				<tr><td><a href=\"$url\">$type</a></td>
					<td><a href=\"$url\">$number</a></td>
					<td>$status</td>
					<td>{$s->getLatitude()},{$s->getLongitude()}</td>
					<td>{$s->getX()},{$s->getY()}</td>
					<td></td>
				</tr>
				";
			}
        ?>
        </tbody>
    </table>
</section>
