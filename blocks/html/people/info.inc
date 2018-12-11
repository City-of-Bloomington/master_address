<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Person $this->person
 */
use Application\Url;

$return_url = Url::current_url(BASE_HOST);
$h = $this->template->getHelper('buttonLink');

$editButton = '';
if (parent::isAllowed('people', 'edit')) {
    $editButton = $h->buttonLink(
        parent::generateUri('people.update', ['id'=>$this->person->id])."?return_url=$return_url",
        $this->_('edit'),
        'edit'
    );
}
$name = parent::escape("{$this->person->firstname} {$this->person->lastname}");

$fields = ['email', 'phone', 'contact_type', 'agency', 'current', 'notification', 'coordination'];
foreach ($fields as $f) {
    $$f = parent::escape($this->person->$f);
}
?>
<section>
    <header>
        <h1><?= $name; ?></h1>
        <div class="tools"><?= $editButton; ?></div>
    </header>

	<table>
		<?php
            foreach ($fields as $f) {
                echo "<tr><th>{$this->_($f)}</th><td>{$$f}</td></tr>";
            }
		?>
	</table>
</section>
