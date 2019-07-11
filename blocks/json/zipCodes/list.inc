<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->zipCodes  Zip Code data
 */
declare (strict_types=1);

echo json_encode($this->zipCodes, JSON_PRETTY_PRINT);
