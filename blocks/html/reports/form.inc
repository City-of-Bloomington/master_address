<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array  $this->report  The report metadata
 * @param array  $this->request Parameter values already submitted
 * @param array  $this->results Raw database result
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= $this->report['title']; ?></h1>
    </header>

    <form method="get">
        <fieldset>
        <?php
            $h = $this->template->getHelper('field');
            foreach ($this->report['params'] as $name=>$v) {
                echo $h->field([
                    'name'     => $name,
                    'id'       => $name,
                    'label'    => $this->_($name),
                    'type'     => $v['type'],
                    'value'    => $this->request[$name],
                    'required' => true
                ]);
            }
        ?>
            <button type="submit" class="submit">Submit</button>
        </fieldset>
    </form>
    <?php
        if ($this->results  ) {
            if (file_exists(SITE_HOME.'/src/Reports/'.$this->report['name'].'/template.inc')) {
                include     SITE_HOME.'/src/Reports/'.$this->report['name'].'/template.inc';
            }
            else {
                $this->_include('reports/output.inc');
            }
        }
    ?>
</section>
