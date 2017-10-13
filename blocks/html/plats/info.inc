<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Plat $this->plat
 */
use Application\Models\Person;

$fields = ['id', 'name', 'type', 'cabinet', 'envelope', 'notes'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = parent::escape($this->plat->$get());
}
$startDate = $this->plat->getStartDate() ? $this->plat->getStartDate()->format(DATE_FORMAT)      : '';
$endDate   = $this->plat->getEndDate  () ? $this->plat->getEndDate  ()->format(DATE_FORMAT)      : '';
$township  = $this->plat->getTownship () ? parent::escape($this->plat->getTownship()->getName()) : '';
?>
<section>
    <header>
        <h1><?= $name; ?></h1>
        <div class="tools">
        <?php
            if (Person::isAllowed('plats', 'update')) {
                $helper = $this->template->getHelper('buttonLink');
                echo $helper->buttonLink(
                    self::generateUri('plats.update', ['id'=>$this->plat->getId()]),
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
