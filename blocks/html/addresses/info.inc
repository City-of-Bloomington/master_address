<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Address $this->address
 * @param string  $this->title
 */
?>
<section>
    <header>
        <h1><?= $this->title ?></h1>
        <div class="tools">
        <?php
            $h       = $this->template->getHelper('buttonLink');
            /*
            $status  = $this->address->status;
            $actions = ['correct','update','readdress','verify'];
            if ($status == 'retired') {
                $actions[] = 'unretire';
                $actions[] = 'reassign';
            }
            else {
                $actions[] = 'retire';
            }
            */
            $actions = ['verify'];

            foreach ($actions as $a) {
                if (parent::isAllowed('addresses', $a)) {
                    echo $h->buttonLink(
                        parent::generateUri("addresses.$a", ['id'=>$this->address->id]),
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
        $this->_include('addresses/partials/nonStreetData.inc');
        $this->_include('addresses/partials/streetData.inc');
    ?>
    </table>
    <?php
        $notes = parent::escape($this->address->notes);
        echo $notes ? "<p>$notes</p>" : '';
    ?>
</section>
