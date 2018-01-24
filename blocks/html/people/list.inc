<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->people
 */
?>
<section>
    <header>
        <h1><?= $this->translate(['person','people',2]); ?></h1>
        <div class="tools">
        <?php
            $return_url = $_SERVER['REQUEST_URI'];
            $helper = $this->template->getHelper('buttonLink');

            if (parent::isAllowed('people', 'add')) {
                echo $helper->buttonLink(
                    self::generateUri('people.update')."?return_url=$return_url",
                    $this->translate('person_add'),
                    'add'
                );
            }
        ?>
        </div>
    </header>

	<table>
		<thead>
			<tr><th><?= $this->_('name'    ); ?></th>
				<th><?= $this->_('email'   ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->people as $person) {
				$name = self::escape("{$person->firstname} {$person->lastname}");
				$url  = self::generateUri('people.view', ['id'=>$person->id]);
				echo "
				<tr><td><a href=\"$url\">$name</a></td>
					<td><a href=\"$url\">{$person->email}</a></td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
</section>
