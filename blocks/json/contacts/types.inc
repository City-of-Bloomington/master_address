<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->types
 */
$types = array();
foreach ($this->types as $type) {
	$types[] = "\"$type\"";
}
$types = implode(',',$types);
echo "{\"types\":[$types]}\n";