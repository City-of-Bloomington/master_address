<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$template = isset($_GET['format']) ? new Template('default', $_GET['format']) : new Template();

if ($template->outputFormat === 'html') {
    $template->blocks[] = new Block('intersections/searchForm.inc');
}

if (!empty($_REQUEST['street'])) {
    $list = IntersectionGateway::find($_REQUEST['street']);
    $template->blocks[] = new Block('streets/streetList.inc', ['streetList'=>$list]);
}

echo $template->render();