<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->intersections
 */
?>
<table>
    <thead>
        <tr><th>Name</th>
            <th>Latitude</th>
            <th>Longitude</th>
    </thead>
    <tbody>
    <?php
        foreach ($this->intersections as $i) {
            echo "
            <tr><td>$i[name]</td>
                <td>$i[latitude]</td>
                <td>$i[longitude]</td>
            </tr>
            ";
        }
    ?>
    </tbody>
</table>
