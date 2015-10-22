<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class IntersectionGateway
{
    public static function intersectingStreets($streetName)
    {
        $sql = "select distinct street.*
                from gis.centerlines c
                join haleyl.intersection_segments s on c.centerline_id=s.centerline_id
                join gis.centerlines x on (s.intersection_id=x.low_intersection_id or s.intersection_id=x.high_intersection_id)
                join eng.mast_street street on x.street_id=street.street_id
                where c.fullname=?";
        $streets = [];

        $zend_db = Database::getConnection();
        $result = $zend_db->fetchAll($sql, [$streetName]);
        foreach ($result as $row) {
            $streets[] = new Street($row);
        }
        return $streets;
    }

    public static function intersections($streetName, $intersectingStreetName=null)
    {
        $sql = "select distinct i.*
                from gis.centerlines c
                join haleyl.intersection_segments s on c.centerline_id=s.centerline_id
                join gis.centerlines x on (s.intersection_id=x.low_intersection_id or s.intersection_id=x.high_intersection_id)
                join haleyl.intersections i on s.intersection_id=i.intersection_id
                where c.fullname=?";
        $params = [$streetName];

        if ($intersectingStreetName) {
            $sql.= ' and x.fullname=?';
            $params[] = $intersectingStreetName;
        }

        $zend_db = Database::getConnection();
        return $zend_db->fetchAll($sql, $params);
    }
}
