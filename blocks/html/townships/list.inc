<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->townships
 */
use Application\Models\Person;
?>
<section>
    <header>
        <h1><?= $this->_(['township', 'townships', count($this->townships)]); ?></h1>
        <div class="tools">
        <?php
            $helper = $this->template->getHelper('buttonLink');
            if (Person::isAllowed('townships', 'add')) {
                echo $helper->buttonLink(
                    self::generateUri('townships.update'),
                    $this->_('township_add'),
                    'add'
                );
            }
        ?>
        </div>
    </header>
	<table>
		<thead>
			<tr><th></th>
				<th><?= $this->_('name'); ?></th>
				<th><?= $this->_('code'); ?></th>
				<th><?= $this->_('quarterCode'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->townships as $township) {
				$editButton = '';
				if (Person::isAllowed('townships', 'edit')) {
                    $editButton = $helper->buttonLink(
                        self::generateUri('townships.update', ['id'=>$township->getId()]),
                        $this->_('township_edit'),
                        'edit'
                    );
				}
				$name        = parent::escape($township->getName());
				$code        = parent::escape($township->getCode());
				$quarterCode = parent::escape($township->getQuarterCode());
				echo "
				<tr><td>$editButton</td>
					<td>$name</td>
					<td>$code</td>
					<td>$quarterCode</td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
</section>
