<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->history  Raw data from database
 */
declare (strict_types=1);
$fields = ['user_id', 'action_date', 'action', 'other_action_details', 'place_verified', 'notes'];
?>
<section>
    <header><h3><?= $this->_('history'); ?></h3></header>
    <table>
        <thead><tr>
        <?php
            foreach ($fields as $f) { echo "<th>{$this->_($f)}</th>"; }
        ?>
        </tr></thead>
        <tbody>
        <?php
            foreach ($this->history as $row) {
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
