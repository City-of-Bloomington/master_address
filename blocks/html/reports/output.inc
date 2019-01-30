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
    <?php
        foreach ($this->results as $row) {
            echo '<tr>';
            foreach ($row as $k=>$v) {
                echo "<td>$v</td>";
            }
            echo '</tr>';
        }
    ?>
    </table>
</section>
