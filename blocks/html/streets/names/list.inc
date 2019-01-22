<?php
/**
 * @copyright 2017-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array  $this->names
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
        $uri = parent::generateUri('streetNames.view', ['id' => $n->id]);

        echo "
        <tr><td><a href=\"$uri\">$direction</a></td>
            <td><a href=\"$uri\">$name</a></td>
            <td><a href=\"$uri\">$post_direction</a></td>
            <td><a href=\"$uri\">$suffix_code</a></td>
        </tr>
        ";
    }
?>
</table>
