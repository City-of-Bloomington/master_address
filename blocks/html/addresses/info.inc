<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Address $this->address
 * @param array   $this->actions   Actions to draw button links for
 * @param string  $this->title
 */
?>
<section>
    <header>
        <h1><?= $this->title ?></h1>
        <div class="tools">
        <?php
            $h       = $this->template->getHelper('buttonLink');
            if ($this->actions) {
                foreach ($this->actions as $a) {
                    if (parent::isAllowed('addresses', $a)) {
                        echo $h->buttonLink(
                            parent::generateUri("addresses.$a", ['id'=>$this->address->id]),
                            $this->_($a),
                            'edit'
                        );

                    }
                }
            }
        ?>
        </div>
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
            'zipplus4'        => 'zipplus4'
        ];
        $required = [
            'type', 'jurisdiction', 'section', 'township'
        ];
        foreach ($fields as $k=>$f) {
            $$k = parent::escape($this->address->$f);
            $class = in_array($k, $required)
                ? $$k ? '' : ' class="missing"'
                : '';
            echo "<tr$class><th>{$this->_($k)}</th><td>{$$k}</td>";
        }
    ?>
    </table>
    <?php
        $notes = parent::escape($this->address->notes);
        echo $notes ? "<p>$notes</p>" : '';
    ?>
</section>
