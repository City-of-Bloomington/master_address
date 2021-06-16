<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
$places = parent::generateUri('places.index');
?>
<nav class="breadcrumbs">
    <a href="<?= $places; ?>"><?= $this->_(['place', 'places', 100]); ?></a>
</nav>
<section>
    <header><h1><?= $this->place->name; ?></h1></header>
    <table>
    <?php
        foreach ($this->place as $k=>$v) {
            $v = parent::escape($v);
            echo "<tr><th>{$this->_($k)}</th><td>$v</td></tr>";
        }
    ?>
    </table>
</section>
