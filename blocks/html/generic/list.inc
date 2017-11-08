<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param string $this->singular The singular form of the objects' tablename
 * @param string $this->plural   The plural form of the objects' tablename
 * @param array $this->list      An array of objects
 * @param array $this->fields    The fields to display for each object
 */
use Application\People\Person;

$tablename = $this->plural;
$single    = $this->singular;
$plural    = $this->plural;

// We don't want to display the ID field.
if (($k = array_search('id', $this->vars['fields'])) !== false) { unset($this->vars['fields'][$k]); }
?>
<section>
    <header>
        <h1><?= $this->_([$single, $plural, count($this->list)]); ?></h1>
        <div class="tools">
        <?php
            $helper = $this->template->getHelper('buttonLink');
            if (Person::isAllowed($tablename, 'add')) {
                echo $helper->buttonLink(
                    self::generateUri("$tablename.update"),
                    $this->_("{$single}_add"),
                    'add'
                );
            }
        ?>
        </div>
    </header>
	<table>
		<thead>
			<tr><th></th>
			<?php
                foreach ($this->fields as $f) {
                    echo "<th>{$this->_($f)}</th>";
                }
			?>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->list as $o) {
                echo '<tr>';
				$editButton = '';
				if (Person::isAllowed($tablename, 'edit')) {
                    $editButton = $helper->buttonLink(
                        self::generateUri("$tablename.update", ['id'=>$o->getId()]),
                        $this->_("{$single}_edit"),
                        'edit'
                    );
				}

				echo "<td>$editButton</td>";
				foreach ($this->fields as $f) {
                    $get = 'get'.ucfirst($f);
                    $val = parent::escape($o->$get($f));

                    echo "<td>$val</td>";
				}
				echo '</tr>';
			}
		?>
		</tbody>
	</table>
</section>
