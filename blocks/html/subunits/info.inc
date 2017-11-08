<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Subunit $this->subunit
 */
use Application\Models\Person;
?>
<section>
    <header>
        <h1><?= $this->_('subunit_info'); ?></h1>
        <div class="tools">
        <?php
            $h       = $this->template->getHelper('buttonLink');
            $status  = $this->subunit->getStatus()->getStatus();
            $actions = ['correct', 'verify', $status=='retired' ? 'unretire' : 'retire'];

            foreach ($actions as $a) {
                if (Person::isAllowed('subunits', $a)) {
                    echo $h->buttonLink(
                        parent::generateUri("subunits.$a"),
                        $this->_($a),
                        'edit'
                    );

                }
            }
        ?>
        </div>
    </header>
    <table class="<?= $status; ?>">
    <?php $this->_include('subunits/partials/subunitData.inc'); ?>
    </table>
</section>
