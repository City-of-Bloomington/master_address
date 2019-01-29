<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->reports
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= $this->_(['report', 'reports', count($this->reports)]); ?></h1>
    </header>

    <table>
    <?php
        foreach ($this->reports as $r) {
            $uri = parent::generateUri('reports.report', ['name'=>$r]);

            echo "<tr><td><a href=\"$uri\">$r</a></td></tr>";
        }
    ?>
    </table>
</section>
