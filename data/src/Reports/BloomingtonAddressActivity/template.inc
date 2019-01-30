<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->results
 */
declare (strict_types=1);
use Application\Url;
?>
<section>
    <header>
        <div class="tools">
        <?php
            $url = new Url(Url::current_url(BASE_HOST));
            $url->format = 'csv';
            $h = $this->template->getHelper('buttonLink');
            echo $h->buttonLink($url, 'CSV', 'download');
        ?>
        </div>
    </header>
    <table>
        <thead><tr>
        <?php
            foreach (array_keys($this->results[0]) as $k) {
                if ($k != 'entity_id') {
                    echo "<th>{$this->_($k)}</th>";
                }
            }
        ?>
        </tr></thead>

        <tbody>
        <?php
            foreach ($this->results as $row) {
                $route = $row['subunit'] ? 'subunits.view' : 'addresses.view';
                $uri   = parent::generateUri($route, ['id'=>$row['entity_id']]);

                echo '<tr>';
                foreach ($row as $k=>$v) {
                    if ($k != 'entity_id') {
                        echo "<td><a href=\"$uri\">$v</a></td>";
                    }
                }
                echo '</tr>';
            }
        ?>
        </tbody>
    </table>
</section>
