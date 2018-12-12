<?php
/**
 * @see public/js/chooser.js
 *
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 *
 * @param array  $this->streets
 * @param Url    $this->callback_url   Url to link person results to
 * @param string $this->callback_field Parameter to add to the callback_url
 * @param string $this->callback_js    Javascript function to call as a callback
 */
?>
<table>
    <tbody>
    <?php
        foreach ($this->streets as $street) {
            $name   = parent::escape($street->__toString());
            $status = parent::escape($street->status);
            $town   = parent::escape($street->town_name);
            $type   = parent::escape($street->type_name);

            if ($this->callback_js) {
                $uri = "javascript:self.opener.{$this->callback_js}('{$street->id}', '{$street->__toString()}');";
            }
            elseif ($this->callback_url) {
                $this->callback_url->{$this->callback_field} = $street->id;
                $uri = $this->callback_url;
            }
            else {
                $uri = parent::generateUri('streets.view', ['id'=>$street->id]);
            }

            echo "
            <tr><td><a href=\"$uri\">{$street->id}</a></td>
                <td><a href=\"$uri\">$name</a></td>
                <td><a href=\"$uri\">$town</a></td>
                <td><a href=\"$uri\">$status</a></td>
                <td><a href=\"$uri\">$type</a></td>
            </tr>
            ";
        }
    ?>
    </tbody>
</table>
