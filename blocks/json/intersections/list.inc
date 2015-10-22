<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->intersections
 */
$intersections = [];
foreach ($this->intersections as $i) {
    $intersections[] = [
        'name'      => $i['name'],
        'latitude'  => $i['latitude'],
        'longitude' => $i['longitude']
    ];
}
echo json_encode($intersections, JSON_PRETTY_PRINT);