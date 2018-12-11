<?php
header('Content-type: application/json; charset=utf-8');
include APPLICATION_HOME.'/templates/errorMessages.inc';
echo $this->includeBlocks();
