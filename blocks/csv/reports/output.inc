<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array  $this->results        Raw database results
 */
declare (strict_types=1);

echo implode(',', array_keys($this->results[0]))."\n";

$out = fopen('php://output', 'w');
foreach ($this->results as $row) {
    fputcsv($out, $row);
}
fclose($out);
