<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param string $this->title
 * @param array  $this->report         Report metadata
 * @param array  $this->results        Raw database results
 * @param bool   $this->disableButtons
 */
declare (strict_types=1);
use Application\Url;
?>
<section>
    <header>
        <h2><?= $this->title; ?></h2>
        <div class="tools">
        <?php
            if (!$this->disableButtons) {
                $url = new Url(Url::current_url(BASE_HOST));
                $url->format = 'csv';
                $h = $this->template->getHelper('buttonLink');
                echo $h->buttonLink($url, 'CSV', 'download');
            }
        ?>
        </div>
    </header>
    <table>
        <thead>
            <tr><th><?= $this->_('action_date'  ); ?></th>
                <th><?= $this->_('address'      ); ?></th>
                <th><?= $this->_('zip'          ); ?></th>
                <th><?= $this->_('action'       ); ?></th>
                <th><?= $this->_('person'       ); ?></th>
                <th><?= $this->_('address_type' ); ?></th>
                <th><?= $this->_('location_id'  ); ?></th>
                <th><?= $this->_('location_type'); ?></th>
                <th><?= $this->_('notes'        ); ?></th>
            </tr>
        </thead>

        <tbody>
        <?php
            foreach ($this->results as $row) {
                $route = $row['subunit'] ? 'subunits.view' : 'addresses.view';
                $uri   = parent::generateUri($route, ['id'=>$row['entity_id']]);

                $date  = new \DateTime($row['action_date']);
                $notes = parent::escape($row['notes']);

                echo "
                <tr><td><a href=\"$uri\">{$date->format(DATE_FORMAT)}</a></td>
                    <td><a href=\"$uri\">{$row['full_address' ]}</a></td>
                    <td><a href=\"$uri\">{$row['zip'          ]}</a></td>
                    <td><a href=\"$uri\">{$row['action'       ]}</a></td>
                    <td><a href=\"$uri\">{$row['person'       ]}</a></td>
                    <td><a href=\"$uri\">{$row['address_type' ]}</a></td>
                    <td><a href=\"$uri\">{$row['location_id'  ]}</a></td>
                    <td><a href=\"$uri\">{$row['location_type']}</a></td>
                    <td>$notes</td>
                </tr>
                ";
            }
        ?>
        </tbody>
    </table>
</section>
