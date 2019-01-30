<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->reports  Array of metadata for all the reports
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
            $uri = parent::generateUri('reports.report', ['name'=>$r['name']]);

            echo "<tr><td><a href=\"$uri\">$r[title]</a></td></tr>";
        }
    ?>
    </table>
</section>
