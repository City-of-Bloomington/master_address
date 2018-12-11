<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->plats
 */
$plats = [];
foreach ($this->plats as $p) {
    $start    = $p->getStartDate();
    $end      = $p->getEndDate();
    $township = $p->getTownship();

    $plats[] = [
        'id'        => $p->getId(),
        'name'      => $p->getName(),
        'type'      => $p->getType(),
        'cabinet'   => $p->getCabinet(),
        'envelope'  => $p->getEnvelope(),
        'startDate' => $start    ? $start->format('Y-m-d') : null,
        'endDate'   => $end      ? $end  ->format('Y-m-d') : null,
        'township'  => $township ? $township->getName()    : null
    ];
}
echo json_encode($plats, JSON_PRETTY_PRINT);
