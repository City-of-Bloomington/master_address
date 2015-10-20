<?php
/**
 * @copyright 2009-2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param StreetList $this->streetList
 */
$streets = ['streets'=>[]];
foreach ($this->streetList as $street) {
    $s = [
        'id'     => $street->getId(),
        'name'   => $street->getStreetName()->__toString(),
        'town'   => $street->getTown()->getDescription(),
        'notes'  => $street->getNotes(),
        'status' => $street->getStatus_code()
    ];
    $streets['streets'][] = $s;
}
echo json_encode($streets, JSON_PRETTY_PRINT);
