<?php
/**
 * @copyright 2009-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Street $this->street
 * @param bool   $this->disableButtons (optional)
 */
use Application\Block;
use Application\Url;

$status = $this->_($this->street->status);
$name   = parent::escape($this->street->__toString());
$notes  = parent::escape($this->street->notes);
$town   = parent::escape($this->street->town_name);
?>
<section>
    <header>
        <h2><?= $name ?></h2>
        <?php
            if (!$this->disableButtons) {
                $helper  = $this->template->getHelper('buttonLink');
                $actions = [];
                foreach (['update', 'changeStatus', 'renumber', 'alias', 'reorder'] as $a) {
                    if (parent::isAllowed('streets', $a)) {
                        $actions[$a] = [
                            'uri'   => parent::generateUri("streets.$a", ['id' => $this->street->id]),
                            'class' => 'edit',
                            'help'  => $this->_("streets.$a", 'messages')
                        ];
                    }
                }
                foreach (['add', 'import'] as $a) {
                    if (parent::isAllowed('addresses', $a)) {
                        $params = http_build_query(['street_id'  => $this->street->id,
                                                    'return_url' => Url::current_url(BASE_HOST)]);
                        $actions["address_$a"] = [
                            'class' => 'add',
                            'help'  => $this->_("addresses.$a", 'messages'),
                            'uri'   => parent::generateUri("addresses.$a").'?'.$params
                        ];
                    }
                }

                if ($actions) {
                    $block = new Block('toolbox.inc', ['actions' => $actions]);
                    echo $block->render('html', $this->template);
                }
            }
        ?>
    </header>
	<table>
        <tr><th><?= $this->_('street_id'); ?></th><td><?= $this->street->id; ?></td></tr>
        <tr><th><?= $this->_('status'   ); ?></th><td><?= $status; ?></td></tr>
        <tr><th><?= $this->_('town'     ); ?></th><td><?= $town;   ?></td></tr>
	</table>
	<p><?= $notes; ?></p>
</section>
