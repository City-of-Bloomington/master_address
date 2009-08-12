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
	public $rationale;
	public $date_changed;

	public function __construct(User $user,array $data)
	{
		$this->user_id = $user->getId();
		$this->action = $data['action'];
		$this->contact_id = $data['contact_id'];
		$this->rationale = $data['rationale'];
		$this->date_changed = date('Y-m-d H:i:s');
	}

	/**
	 * Returns the array of data, ready to insert into the database
	 *
	 * @return array
	 */
	public function getData()
	{
		return array('user_id'=>$this->user_id,'action'=>$this->action,
						'contact_id'=>$this->contact_id,'rationale'=>$this->rationale,
						'date_changed'=>$this->date_changed);
	}
}