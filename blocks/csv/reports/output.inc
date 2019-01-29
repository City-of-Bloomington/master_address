<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->result
 */
declare (strict_types=1);

echo implode(',', array_keys($this->result[0]))."\n";

$out = fopen('php://output', 'w');
foreach ($this->result as $row) {
    fputcsv($out, $row);
}
fclose($out);
