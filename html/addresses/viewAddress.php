<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET address_id
 */
$address = new Address($_GET['address_id']);

$template = new Template();
$template->blocks[] = new Block('addresses/addressInfo.inc',array('address'=>$address));
echo $template->render();