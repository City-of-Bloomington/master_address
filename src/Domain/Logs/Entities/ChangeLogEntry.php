<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Logs\Entities;

class ChangeLogEntry
{
    public $id;
    public $entity_id;
    public $person_id;
    public $contact_id;
    public $action_date;
    public $action;
    public $notes;

    // Foreign key fields
    public $person_firstname;
    public $person_lastname;
    public $contact_firstname;
    public $contact_lastname;

    public function __construct(?array $data=null)
    {
        if ($data) {
            if (!empty($data['id'        ])) { $this->id         = (int)$data['id'        ]; }
            if (!empty($data['entity_id' ])) { $this->entity_id  = (int)$data['entity_id' ]; }
            if (!empty($data['person_id' ])) { $this->person_id  = (int)$data['person_id' ]; }
            if (!empty($data['contact_id'])) { $this->contact_id = (int)$data['contact_id']; }

            if (!empty($data['action'           ])) { $this->action            = $data['action']; }
            if (!empty($data['notes'            ])) { $this->notes             = $data['notes' ]; }
            if (!empty($data['person_firstname' ])) { $this->person_firstname  = $data['person_firstname' ]; }
            if (!empty($data['person_lastname'  ])) { $this->person_lastname   = $data['person_lastname'  ]; }
            if (!empty($data['contact_firstname'])) { $this->contact_firstname = $data['contact_firstname']; }
            if (!empty($data['contact_lastname' ])) { $this->contact_lastname  = $data['contact_lastname' ]; }

            if (!empty($data['action_date'])) { $this->setActionDate($data['action_date']); }
        }
    }

    public function setActionDate(\DateTime $date) { $this->action_date = $date; }

    /**
     * Instantiates an entry using an array of strings
     *
     * This is typically called from a database repository.
     * The constructor requires property values to already be of the correct type.
     * This function converts string values into their correct type.
     */
    public static function hydrate(array $row): ChangeLogEntry
    {
        if (!empty($row['action_date'])) { $row['action_date'] = new \DateTime($row['action_date']); }
        return new ChangeLogEntry($row);
    }
}
