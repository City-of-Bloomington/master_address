<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (!empty( $_REQUEST['format'])) {
    $template = new Template('default', $_REQUEST['format']);
}
else {
    $template = new Template();
}
if ($template->outputFormat == 'html') {
    $template->blocks[] = new Block('locations/verifyForm.inc');
}

if (!empty($_REQUEST['address'])) {
    $data = LocationList::verify($_REQUEST['address']);
    if ($data) {
        $template->blocks[] = new Block('locations/info.inc', ['location'=>$data]);
    }
    else {
        $template->blocks[] = new Block('locations/invalid.inc');
    }
}
echo $template->render();
