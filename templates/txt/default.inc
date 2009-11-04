<?php
header('Content-type: text/csv');
header('Content-Disposition: attachment;filename="report.txt"');
echo $this->includeBlocks();