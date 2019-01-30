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
                    switch ($k) {
                        case 'entity_id': break;

                        case 'action_date':
                            $d = new \DateTime($v);
                            echo "<td><a href=\"$uri\">{$d->format(DATE_FORMAT)}</a></td>";
                        break;

                        default:
                            echo "<td><a href=\"$uri\">$v</a></td>";
                    }
                }
                echo '</tr>';
            }
        ?>
        </tbody>
    </table>
</section>
