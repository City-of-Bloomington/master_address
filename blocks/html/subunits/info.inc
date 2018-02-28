<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Subunit $this->subunit
 * @param array   $this->actions   Actions to draw button links for
 * @param string  $this->title
 */
?>
<section>
    <header>
        <h1><?= $this->title; ?></h1>
        <div class="tools">
        <?php
            if ($this->actions) {
                $h = $this->template->getHelper('buttonLink');
                foreach ($this->actions as $a) {
                    if (parent::isAllowed('subunits', $a)) {
                        echo $h->buttonLink(
                            parent::generateUri("subunits.$a", ['id' => $this->subunit->id]),
                            $this->_($a),
                            'edit'
                        );
                    }
                }
            }
        ?>
        </div>
    </header>
    <table class="<?= $this->subunit->status; ?>">
    <?php $this->_include('subunits/partials/subunitData.inc'); ?>
    </table>
</section>
