<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET street_id
 */
$street = new Street($_GET['street_id']);

$template = new Template();
$template->blocks[] = new Block('streets/streetInfo.inc',array('street'=>$street));
$template->blocks[] = new Block('streets/streetNameList.inc',
								array('streetNameList'=>