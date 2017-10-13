<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Subdivision $this->subdivision
 */
use Application\Models\Person;

$fields = ['id', 'name', 'phase', 'status'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = parent::escape($this->subdivision->$get());
}
$township = $this->subdivision->getTownship () ? parent::escape($this->subdivision->getTownship()->getName()) : '';
?>
<section>
    <header>
        <h1><?= $name; ?></h1>
        <div class="tools">
        <?php
            if (Person::isAllowed('subdivisions', 'update')) {
                $helper = $this->template->getHelper('buttonLink');
                echo $helper->buttonLink(
                    self::generateUri('subdivisions.update', ['id'=>$id]),
                    $this->_('subdivision_edit'),
                    'edit'
                );
            }
        ?>
        </div>
    </header>
    <div>
        <table>
        <?php
            foreach (['township', 'phase', 'status'] as $f) {
                echo "<tr><th>{$this->_($f)}</th><td>{$$f}</td></tr>";
            }
        ?>
        </table>
    </div>
</section>
