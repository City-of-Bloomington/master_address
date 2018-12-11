<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Address $this->address
 */
$streetUrl  = parent::generateUri(  'streets.view', ['id'=>$this->address->street_id]);
$addressUrl = parent::generateUri('addresses.view', ['id'=>$this->address->id       ]);
?>
<nav class="breadcrumbs">
    <a href="<?= BASE_URL; ?>/addresses"><?= $this->_(['address', 'addresses', 10]); ?></a>
    <a href="<?= $streetUrl;  ?>"><?= $this->address->streetName();   ?></a>
    <a href="<?= $addressUrl; ?>"><?= $this->address->streetNumber(); ?></a>
</nav>
