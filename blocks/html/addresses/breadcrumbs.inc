<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Address $this->address optional
 * @param Street  $this->street  optional
 * @param string  $this->action  optional
 */

if ($this->address) {
    $address = parent::escape($this->address);
    $street_id = $this->address->street_id;
    $street  = parent::escape($this->address->streetName());
}
elseif ($this->street) {
    $street_id = $this->street->id;
    $street = parent::escape($this->street);
}
?>
<div class="breadcrumbs">
	<ul><li><a href="<?= parent::generateUri('streets.index'); ?>">Streets</a></li>
		<?php
			if (isset($street)) {
                $uri = parent::generateUri('streets.view', ['id'=>$street_id]);
				echo "<li><a href=\"$uri\">$street</a></li>";
			}

			if (isset($address)) {
				$number = $this->address->streetNumber();
				$uri = parent::generateUri('addresses.view', ['id'=>$this->address->id]);
                echo "<li><a href=\"$uri\">$number</a></li>";
			}

			if (isset($this->action)) {
				$action = $this->_($this->action);
				echo "<li>$action</li>";
			}
		?>
	</ul>
</div>
