<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

$stateRoadList = new StateRoadList();
$stateRoadList->find();

$template = new Template();
$template->blocks[] = new Block('stateRoads/stateRoadList.inc',array('stateRoadList'=>$stateRoadList));
echo $template->render();
