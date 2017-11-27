<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->names
 */
declare (strict_types=1);
?>
<table>
<?php
    $fields = ['name', 'direction', 'postDirection', 'suffixCode'];
    foreach ($this->names as $n) {
        foreach ($fields as $f) {
            $get = 'get'.ucfirst($f);
            $$f  = parent::escape($n->$get());
        }
        echo "
        <tr><td>$direction</td>
            <td>$name</td>
            <td>$postDirection</td>
            <td>$suffixCode</td>
        </tr>
        ";
    }
?>
</table>
