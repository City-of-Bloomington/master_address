<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use Application\Block;
?>
<nav class="breadcrumbs">
    <a href="<?= parent::generateUri('places.index'); ?>"><?= $this->_(['place', 'places', 100]); ?></a>
</nav>
<section>
    <header><h1><?= $this->place->name; ?></h1></header>
    <?php
        if (!$this->disableButtons && parent::isAllowed('places', 'update')) {
            $block = new Block('toolbox.inc', ['actions' => [
                'update' => [
                    'uri'   => parent::generateUri("places.update", ['id'=>$this->place->id]),
                    'class' => 'edit',
                    'help'  => $this->_("places.update", 'messages')
                ]
            ]]);
            echo $block->render('html', $this->template);
        }
    ?>
    <table>
    <?php
        foreach ($this->place as $k=>$v) {
            $v = parent::escape($v);
            echo "<tr><th>{$this->_($k)}</th><td>$v</td></tr>";
        }
    ?>
    </table>
</section>
