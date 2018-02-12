<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->names
 */
declare (strict_types=1);
?>
<table>
<?php
    $fields = ['name', 'direction', 'post_direction', 'suffix_code'];
    foreach ($this->names as $n) {
        foreach ($fields as $f) {
            $$f = parent::escape($n->$f);
        }
        echo "
        <tr><td>$direction</td>
            <td>$name</td>
            <td>$post_direction</td>
            <td>$suffix_code</td>
        </tr>
        ";
    }
?>
</table>
