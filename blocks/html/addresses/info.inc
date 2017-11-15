<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Address $this->address
 */
use Application\People\Person;
?>
<section>
    <header>
        <h1><?= $this->_('address_info'); ?></h1>
        <div class="tools">
        <?php
            $h       = $this->template->getHelper('buttonLink');
            $status  = $this->address->getStatus()->getStatus();
            $actions = ['correct','update','readdress','verify'];
            if ($status == 'retired') {
                $actions[] = 'unretire';
                $actions[] = 'reassign';
            }
            else {
                $actions[] = 'retire';
            }

            foreach ($actions as $a) {
                if (Person::isAllowed('addresses', $a)) {
                    echo $h->buttonLink(
                        parent::generateUri("addresses.$a").'?id='.$this->address->getId(),
                        $this->_($a),
                        'edit'
                    );

                }
            }
        ?>
        </div>
    </header>
    <table class="<?= $status; ?>">
    <?php
        $this->_include('addresses/partials/streetData.inc');
        $this->_include('addresses/partials/nonStreetData.inc');
    ?>
    </table>
    <?php
        $notes = parent::escape($this->address->getNotes());
        echo $notes ? "<p>$notes</p>" : '';
    ?>
</section>
