<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\ChangeLogs;

class Metadata
{
    // Map the action name to the log message
	public static $actions = [
        'activate'  => 'activated',
        'add'       => 'added',
        'alias'     => 'added alias',
        'assign'    => 'assigned',
        'change'    => 'changed street name',
        'correct'   => 'corrected',
        'create'    => 'created',
        'move'      => 'moved to location',
        'propose'   => 'proposed',
        'readdress' => 'readdressed',
        'reassign'  => 'reassigned',
        'retire'    => 'retired',
        'update'    => 'updated',
        'unretire'  => 'unretired',
        'verify'    => 'verified'
    ];
    
    public static function sqlForLog(string $entityName)
    {
        return "select l.{$entityName}_id as entity_id,
                       l.id, l.person_id, l.contact_id, l.action_date, l.action, l.notes,
                       p.firstname as  person_firstname, p.lastname as  person_lastname,
                       c.firstname as contact_firstname, c.lastname as contact_lastname
                from {$entityName}_change_log l
                left join people p on l.person_id=p.id
                left join people c on l.contact_id=p.id
                where {$entityName}_id=?
                order by l.action_date desc";
    }
}
