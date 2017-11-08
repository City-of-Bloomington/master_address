<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Person $this->person
 */
use Application\People\Person;
use Blossom\Classes\Url;

$return_url = Url::current_url(BASE_HOST);
$h = $this->template->getHelper('buttonLink');

$username = $this->person->getUsername();
if (!$username && Person::isAllowed('users')) {
	$username = $h->buttonLink(
		parent::generateUri('users.update', ['id'=>$this->person->getId()])."?return_url=$return_url",
		$this->_('user_add'),
		'add'
	);
}

$editButton = '';
if (Person::isAllowed('people', 'edit')) {
    $editButton = $h->buttonLink(
        parent::generateUri('people.update', ['id'=>$this->person->getId()])."?return_url=$return_url",
        $this->_('edit'),
        'edit'
    );
}
$name = parent::escape($this->person->getFullname());

$fields = ['email', 'phone', 'type', 'agency', 'current', 'notification', 'coordination'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = parent::escape($this->person->$get());
}
?>
<section>
    <header>
        <h1><?= $name; ?></h1>
        <div class="tools"><?= $editButton; ?></div>
    </header>

	<table>
		<tr><th><?= $this->_('username'); ?></th><td><?= $username; ?></td></tr>
		<?php
            foreach ($fields as $f) {
                echo "<tr><th>{$this->_($f)}</th><td>{$$f}</td></tr>";
            }
		?>
	</table>
</section>
