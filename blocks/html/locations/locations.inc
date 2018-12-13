<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array   $this->locations
 * @param boolean $this->disableButtons (optional)
 */
use Application\Block;
use Application\Url;

$fields = [
    'location_id', 'status', 'type_code',
    'mailable', 'occupiable', 'group_quarter', 'active',
    'trash_day', 'recycle_week',
    'address', 'subunit'
];
?>
<section>
    <header>
        <h2><?= $this->_(['location', 'locations', count($this->locations)]); ?></h2>
    </header>
    <table>
        <thead>
            <tr><?php foreach ($fields as $f) { echo "<th>{$this->_($f)}</th>"; } ?></tr>
        </thead>
        <tbody>
        <?php
            foreach ($this->locations as $l) {
                echo '<tr>';
                foreach ($fields as $f) {
                    $v = parent::escape($l->$f);
                    echo $f == 'location_id'
                        ? "<th>$v</th>"
                        : "<td>$v</td>";
                }
                echo '</tr>';
            }
        ?>
        </tbody>
    </table>
</section>
