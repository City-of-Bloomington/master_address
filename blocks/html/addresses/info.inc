<?php
/**
 * @copyright 2009-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Address $this->address
 * @param string  $this->title
 */
use Application\Block;

?>
<section>
    <header>
        <h1><?= $this->address ?> (<?= $this->address->status; ?>)</h1>
        <?php
            if (!$this->disableButtons) {
                $h       = $this->template->getHelper('buttonLink');
                $actions = [];
                foreach (['verify', 'changeStatus', 'correct', 'update', 'readdress'] as $a) {
                    if (parent::isAllowed('addresses', $a)) {
                        $actions[$a] = [
                            'uri'   => parent::generateUri("addresses.$a", ['id'=>$this->address->id]),
                            'class' => 'edit',
                            'help'  => $this->_("addresses.$a", 'messages')
                        ];
                    }

                }

                foreach (['add', 'import'] as $a) {
                    if (parent::isAllowed('subunits', $a)) {
                        $actions["subunit_$a"] = [
                            'uri'   => parent::generateUri("subunits.$a").'?address_id='.$this->address->id,
                            'class' => 'add',
                            'help'  => $this->_("subunits.$a", 'messages')
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
    <table class="<?= $this->address->status; ?>">
    <?php
        $fields = [
            'id'              => 'id',
            'address2'        => 'address2',
            'type'            => 'address_type',
            'jurisdiction'    => 'jurisdiction_name',
            'plat'            => 'plat_name',
            'plat_lot_number' => 'plat_lot_number',
            'township'        => 'township_name',
            'section'         => 'section',
            'quarter'         => 'quarter_section',
            'state_plane_x'   => 'state_plane_x',
            'state_plane_y'   => 'state_plane_y',
            'latitude'        => 'latitude',
            'longitude'       => 'longitude',
            'usng'            => 'usng',
            'street_id'       => 'street_id',
            'city'            => 'city',
            'zip'             => 'zip',
            'zipplus4'        => 'zipplus4',
            'location_id'     => 'location_id'
        ];
        $required = [
            'type', 'jurisdiction', 'section', 'township'
        ];
        foreach ($fields as $k=>$f) {
            $label = $k == 'id' ? 'address_id' : $k;
            $$k    = parent::escape($this->address->$f);
            $class = in_array($k, $required)
                ? $$k ? '' : ' class="missing"'
                : '';
            echo "<tr$class><th>{$this->_($label)}</th><td>{$$k}</td>";
        }
    ?>
    </table>
    <?php
        $notes = parent::escape($this->address->notes);
        echo $notes ? "<p>$notes</p>" : '';
    ?>
</section>
