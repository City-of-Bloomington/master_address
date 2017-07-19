<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->jurisdictions
 */
use Application\Models\Person;
?>
<section>
    <header>
        <h1><?= $this->_(['jurisdiction', 'jurisdictions', count($this->jurisdictions)]); ?></h1>
        <div class="tools">
        <?php
            $helper = $this->template->getHelper('buttonLink');
            if (Person::isAllowed('jurisdictions', 'add')) {
                echo $helper->buttonLink(
                    self::generateUri('jurisdictions.update'),
                    $this->_('jurisdiction_add'),
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
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->jurisdictions as $jurisdiction) {
				$editButton = '';
				if (Person::isAllowed('jurisdictions', 'edit')) {
                    $editButton = $helper->buttonLink(
                        self::generateUri('jurisdictions.update', ['id'=>$jurisdiction->getId()]),
                        $this->_('jurisdiction_edit'),
                        'edit'
                    );
				}
				$name = parent::escape($jurisdiction->getName());
				echo "
				<tr><td>$editButton</td>
					<td>$name</td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
</section>
