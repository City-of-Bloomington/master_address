<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
include './bootstrap.inc';

$sql    = "select l.*, s.records
           from address_status l
           join (
               select address_id, count(*) as records
               from address_status group by address_id) s on l.address_id = s.address_id
           where end_date is not null and records = 1";

$result = $pdo->query($sql);

$sql    = "insert into address_status (address_id, status, start_date) values(?, ?, ?)";
$insert = $pdo->prepare($sql);

$sql    = "update address_status set end_date=null, start_date=? where id=?";
$update = $pdo->prepare($sql);

foreach ($result as $r) {
    $insert->execute([ $r['address_id'], 'current', $r['start_date'] ]);
    $update->execute([ $r['end_date'], $r['id'] ]);
}
