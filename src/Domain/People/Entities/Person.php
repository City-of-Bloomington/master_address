<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\People\Entities;

class Person implements \JsonSerializable
{
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $phone;
    public $agency;
    public $contact_type;

    public $current;
    public $notification;
    public $coordination;

    public function __construct(?array $data=null)
    {
        if ($data) {
            if (!empty($data['id'       ])) { $this->id        = (int)$data['id'  ]; }
            if (!empty($data['firstname'])) { $this->firstname = $data['firstname']; }
            if (!empty($data['lastname' ])) { $this->lastname  = $data['lastname' ]; }
            if (!empty($data['email'    ])) { $this->email     = $data['email'    ]; }
            if (!empty($data['phone'    ])) { $this->phone     = $data['phone'    ]; }
            if (!empty($data['agency'   ])) { $this->agency    = $data['agency'   ]; }

            if (!empty($data['contact_type'])) { $this->contact_type = $data['contact_type']; }
            if (!empty($data['current'     ])) { $this->current      = $data['current'     ] ? true : false; }
            if (!empty($data['notification'])) { $this->notification = $data['notification'] ? true : false; }
            if (!empty($data['coordination'])) { $this->coordination = $data['coordination'] ? true : false; }
        }
    }

    public function __toString()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function jsonSerialize()
    {
        return array_merge((array)$this, ['fullname'=>$this->__toString()]);
    }

	public static function CONTACT_TYPES(): array
	{
        return [
            'Address Coordinator',
            'CBU',
            'Developer',
            'E911 Administrator',
            'Emergency Services',
            'GIS',
            'Government Agency',
            'Property Owner',
            'Utility Provider'
        ];
	}
}
