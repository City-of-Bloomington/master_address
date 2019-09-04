<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
include '../../bootstrap.inc';

$tables = ['address', 'location', 'subunit'];

foreach ($tables as $table) {
    echo "Fixing {$table} status\n";
    $sql    = "select l.*, s.records
            from {$table}_status l
            join (
                select {$table}_id, count(*) as records
                from {$table}_status group by {$table}_id) s on l.{$table}_id = s.{$table}_id
            where end_date is not null and records = 1";

    $result = $pdo->query($sql);

    $sql    = "insert into {$table}_status ({$table}_id, status, start_date) values(?, ?, ?)";
    $insert = $pdo->prepare($sql);

    $sql    = "update {$table}_status set end_date=null, start_date=? where id=?";
    $update = $pdo->prepare($sql);

    foreach ($result as $r) {
        $insert->execute([ $r["{$table}_id"], 'current', $r['start_date'] ]);
        $update->execute([ $r['end_date'], $r['id'] ]);
    }
}
