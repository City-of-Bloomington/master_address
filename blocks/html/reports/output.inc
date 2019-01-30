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

if (file_exists(SITE_HOME.'/src/Reports/'.$this->report['name'].'/template.inc')) {
    include     SITE_HOME.'/src/Reports/'.$this->report['name'].'/template.inc';
}
else {
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
                echo "<th>{$this->_($k)}</th>";
            }
        ?>
        </tr></thead>

        <tbody>
        <?php
            foreach ($this->results as $row) {
                echo '<tr>';
                foreach ($row as $k=>$v) {
                    echo "<td>$v</td>";
                }
                echo '</tr>';
            }
        ?>
        </tbody>
    </table>
</section>
<?php
}
