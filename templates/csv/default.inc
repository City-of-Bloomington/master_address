<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param string $this->title
 */
declare (strict_types=1);
header('Content-type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=\"{$this->title}.csv\"");
echo $this->includeBlocks();
