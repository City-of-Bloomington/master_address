<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$precinctList = new PrecinctList();
$precinctList->find();

$template = new Template();
$template->blocks[] = new Block('precincts/precinctList.inc',array('precinctList'=>$precinctList));
echo $template->render();
