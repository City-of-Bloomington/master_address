<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array  $this->names
 * @param Url    $this->callback_url   Url to link person results to
 * @param string $this->callback_field Parameter to add to the callback_url
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

        if ($this->callback_url) {
            $this->callback_url->{$this->callback_field} = $n->id;
            $uri = $this->callback_url;
        }
        else {
            $uri = parent::generateUri('streetNames.view', ['id' => $n->id]);
        }

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
