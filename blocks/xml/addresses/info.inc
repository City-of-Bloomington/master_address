<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Address $this->address
 */
echo "
<address>
";
foreach ($this->address as $k=>$v) {
    $v = parent::escape($v);
    echo "\t<$k>$v</$k>\n";
}
echo "</address>";
