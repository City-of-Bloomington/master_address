<?php
/**
 * @copyright 2009-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Subunit $this->subunit
 * @param array   $this->actions   Actions to draw button links for
 * @param string  $this->title
 */
use Application\Block;
?>
<section>
    <header>
        <h1><?= $this->title; ?></h1>
        <?php
            $actions = [];
            foreach (['verify', 'changeStatus', 'correct', 'update'] as $a) {
                if (parent::isAllowed('subunits', $a)) {
                    $actions[$a] = [
                        'uri'   => parent::generateUri("subunits.$a", ['id' => $this->subunit->id]),
                        'class' => 'edit',
                        'help'  => $this->_("subunits.$a", 'messages')
                    ];
                }
            }
            if ($actions) {
                $block = new Block('toolbox.inc', ['actions'=>$actions]);
                echo $block->render('html', $this->template);
            }
        ?>
    </header>
    <table class="<?= $this->subunit->status; ?>">
    <?php $this->_include('subunits/partials/subunitData.inc'); ?>
    </table>
</section>
