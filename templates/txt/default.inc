<?php
header('Content-type: text/csv');
header('Content-Disposition: attachment;filename="report.txt"');
while (ob_get_level() > 0) {
	ob_end_flush();
}
echo $this->includeBlocks();