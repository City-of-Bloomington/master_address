<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Plat $this->plat
 */
$fields = ['id', 'name', 'cabinet', 'envelope', 'notes'];
foreach ($fields as $f) {
    $$f = parent::escape($this->plat->$f);
}
$startDate = $this->plat->start_date ? $this->plat->start_date->format(DATE_FORMAT) : '';
$endDate   = $this->plat->end_date   ? $this->plat->end_date  ->format(DATE_FORMAT) : '';
$type      = parent::escape($this->plat->plat_type);
$township  = parent::escape($this->plat->township_name);
?>
<section>
    <header>
        <h1><?= $name; ?></h1>
        <div class="tools">
        <?php
            if (parent::isAllowed('plats', 'update')) {
                $helper = $this->template->getHelper('buttonLink');
                echo $helper->buttonLink(
                    parent::generateUri('plats.update', ['id'=>$id]),
                    $this->_('plat_edit'),
                    'edit'
                );
            }
        ?>
        </div>
    </header>
    <div>
        <table>
        <?php
            foreach (['township', 'startDate', 'endDate', 'type', 'cabinet', 'envelope'] as $f) {
                echo "<tr><th>{$this->_($f)}</th><td>{$$f}</td></tr>";
            }
        ?>
        </table>
        <p><?= $notes; ?></p>
    </div>
</section>
