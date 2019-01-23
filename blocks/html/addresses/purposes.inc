<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->purposes
 */
declare (strict_types=1);
?>
<section>
    <header><h2><?= $this->_('purposes'); ?></h2></header>
    <table>
    <?php
        foreach ($this->purposes as $p) {
            echo "<tr><th>$p[purpose_type]</th><td>$p[name]</td></tr>";
        }
    ?>
    </table>
</section>
