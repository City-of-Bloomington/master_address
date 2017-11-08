<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Street $this->street
 */
use Application\People\Person;

$name   = parent::escape($this->street->getName());
$status = $this->_($this->street->getStatus());
$notes  = parent::escape($this->street->getNotes());
$town   = parent::escape($this->street->getTown_id() ? $this->street->getTown()->getName() : '');
?>
<section>
    <header>
        <h2><?= $this->_('street_info'); ?></h2>
    </header>
	<table>
        <tr><th><?= $this->_('id'    ); ?></th><td><?= $this->street->getId(); ?></td></tr>
        <tr><th><?= $this->_('name'  ); ?></th><td><?= $name;   ?></td></tr>
        <tr><th><?= $this->_('status'); ?></th><td><?= $status; ?></td></tr>
        <tr><th><?= $this->_('town'  ); ?></th><td><?= $town;   ?></td></tr>
	</table>
	<p><?= $notes; ?></p>
</section>
