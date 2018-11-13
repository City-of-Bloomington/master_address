<?php
/**
 * @see public/js/chooser.js
 *
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 *
 * @param array  $this->people
 * @param Url    $this->callback_url   Url to link person results to
 * @param string $this->callback_field Parameter to add to the callback_url
 * @param string $this->callback_js    Javascript function to call as a callback
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
                    parent::generateUri('people.update')."?return_url=$return_url",
                    $this->translate('person_add'),
                    'add'
                );
            }
        ?>
        </div>
    </header>

	<table>
		<thead>
			<tr><th><?= $this->_('name' ); ?></th>
				<th><?= $this->_('email'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->people as $person) {
				$name = parent::escape("{$person->firstname} {$person->lastname}");
				if ($this->callback_js) {
                    $url = "javascript:self.opener.{$this->callback_js}('{$person->id}', '{$person->__toString()}');";
				}
				elseif ($this->callback_url) {
                    $this->callback_url->{$this->callback_field} = $person->id;
                    $url = $this->callback_url->__toString();
				}
				else {
                    $url = parent::generateUri('people.view', ['id'=>$person->id]);
                }
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
