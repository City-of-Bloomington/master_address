<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->alt_names  Raw data from database
 */
declare (strict_types=1);
$fields = ['alt_name', 'name_language', 'alt_type', 'alt_name_rank', 'status', 'comments'];
?>
<section>
    <header><h3><?= $this->_('alt_names'); ?></h3></header>
    <table>
        <thead><tr>
        <?php
            foreach ($fields as $k) { echo "<th>{$this->_($k)}</th>"; }
        ?>
        </tr></thead>
        <tbody>
        <?php
            foreach ($this->alt_names as $row) {
                echo '<tr>';
                foreach ($fields as $f) {
                    echo "<td>$row[$f]</td>";
                }
                echo '</tr>';
            }
        ?>
        </tbody>
    </table>
</section>
