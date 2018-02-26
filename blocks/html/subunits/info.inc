<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Subunit $this->subunit
 */
$title = parent::escape("{$this->subunit->type_code} {$this->subunit->identifier}");
?>
<section>
    <header>
        <h1><?= $title; ?></h1>
        <div class="tools">
        <?php
            $h       = $this->template->getHelper('buttonLink');
            /*
            $status  = $this->subunit->getStatus()->getStatus();
            $actions = ['correct', 'verify', $status=='retired' ? 'unretire' : 'retire'];
            */
            $actions = ['verify', 'retire'];
            foreach ($actions as $a) {
                if (parent::isAllowed('subunits', $a)) {
                    echo $h->buttonLink(
                        parent::generateUri("subunits.$a", ['id' => $this->subunit->id]),
                        $this->_($a),
                        'edit'
                    );
                }
            }
        ?>
        </div>
    </header>
    <table class="<?= $this->subunit->status; ?>">
    <?php $this->_include('subunits/partials/subunitData.inc'); ?>
    </table>
</section>
