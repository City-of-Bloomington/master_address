<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Street $this->street
 */
$name = parent::escape(implode(' ', [
    $this->street->direction,
    $this->street->name,
    $this->street->post_direction,
    $this->street->suffix_code
]));
$status = $this->_($this->street->status);
$notes  = parent::escape($this->street->notes);
$town   = parent::escape($this->street->town_name);
?>
<section>
    <header>
        <h2><?= $this->_('street_info'); ?></h2>
        <div class="tools">
        <?php
            /*
            $actions = ['verify', 'correct', 'changeStatus'];
            $helper = $this->template->getHelper('buttonLink');
            foreach ($actions as $a) {
                if (parent::isAllowed('streets', $a)) {
                    echo $helper->buttonLink(
                        parent::generateUri("streets.$a").'?id='.$this->street->id,
                        $this->_($a),
                        'edit'
                    );
                }
            }
            */
        ?>
        </div>
    </header>
	<table>
        <tr><th><?= $this->_('id'    ); ?></th><td><?= $this->street->id; ?></td></tr>
        <tr><th><?= $this->_('name'  ); ?></th><td><?= $name;   ?></td></tr>
        <tr><th><?= $this->_('status'); ?></th><td><?= $status; ?></td></tr>
        <tr><th><?= $this->_('town'  ); ?></th><td><?= $town;   ?></td></tr>
	</table>
	<p><?= $notes; ?></p>
</section>
