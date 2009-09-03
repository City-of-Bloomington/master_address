<?php
/**
 * A class to encapsulate the information logged whenever someone makes a change
 * to something in the database.  All of the log tables will have these same fields
 *
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class ChangeLogEntry
{
	public $user_id;
	public $action;
	public $contact_id;
	public $notes;
	public $action_date;

	public $type;
	public $id;

	private $user;
	private $contact;

	private $actions = array('activate'=>'activated','assign'=>'assigned',
							 'add'=>'added','create'=>'created','propose'=>'proposed',
							 'change'=>'changed','correct'=>'corrected',
							 'move'=>'moved to location',
							 'readdress'=>'readdressed','reassign'=>'reassigned',
							 'remove'=>'removed','retire'=>'retired',
							 'retire location'=>'retired location',
							 'unretire'=>'unretired',
							 'unretire location'=>'unretired location',
							 'verify'=>'verified');

	/**
	 * Creates a new entry for the change log
	 *
	 * Only the User is required.  You must pass the user in the first parameter.
	 * You can pass all the of the data as an array in the first paramter....
	 * Or you can pass the User and the data seperately
	 *
	 * @param User|array $user
	 * @param array $data
	 */
	public function __construct($user,array $data=null)
	{
		if ($user instanceof User) {
			$this->user = $user;
			$this->user_id = $user->getId();
		}
		elseif (is_array($user)) {
			$this->parseArray($user);
		}
		else {
			$this->user_id = $user;
		}

		if ($data) {
			$this->parseArray($data);
		}


		if (!$this->action_date) {
			$this->action_date = new Date();
		}

		if (!$this->user_id) {
			throw new Exception('logEntry/missingUser');
		}

		if (!$this->action) {
			throw new Exception('logEntry/missingAction');
		}
	}

	/**
	 * @param array $data
	 */
	private function parseArray(array $data)
	{
		foreach ($data as $field=>$value) {
			switch ($field) {
				case 'action_date':
					$value = new Date($value);
					break;
				case 'action':
					if (in_array($value,array_keys($this->actions))) {
						$value = $this->actions[$value];
					}
					elseif (!in_array($value,$this->actions)) {
						throw new Exception('logEntry/unknownAction');
					}
					break;
			}
			$this->$field = $value;
		}
	}

	/**
	 * Returns the array of data, ready to insert into the database
	 *
	 * @return array
	 */
	public function getData()
	{
		return array('user_id'=>$this->user_id,'action'=>$this->action,
					 'contact_id'=>$this->contact_id,'notes'=>$this->notes,
					 'action_date'=>$this->action_date->format('Y-m-d H:i:s'));
	}

	/**
	 * @return User
	 */
	public function getUser()
	{
		if (!$this->user) {
			$this->user = new User($this->user_id);
		}
		return $this->user;
	}

	/**
	 * @return Contact
	 */
	public function getContact()
	{
		if (!$this->contact) {
			$this->contact = new Contact($this->contact_id);
		}
		return $this->contact;
	}
}