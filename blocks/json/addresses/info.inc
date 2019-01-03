<?php
/**
 * @copyright 2013-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param InfoResponse $this->info
 */
$this->info->address->streetAddress = $this->info->address->__toString();
echo json_encode($this->info, JSON_PRETTY_PRINT);
