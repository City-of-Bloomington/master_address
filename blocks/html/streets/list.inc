<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array  $this->streets
 * @param Url    $this->callback_url   Url to link person results to
 * @param string $this->callback_field Parameter to add to the callback_url
 */
?>
<table>
    <tbody>
    <?php
        foreach ($this->streets as $street) {
            $name   = parent::escape($street->__toString());
            $status = parent::escape($street->status);
            $town   = parent::escape($street->town_name);

            if ($this->callback_url) {
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
            </tr>
            ";
        }
    ?>
    </tbody>
</table>
