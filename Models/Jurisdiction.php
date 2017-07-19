<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;
use Blossom\Classes\ActiveRecord;

class Jurisdiction extends ActiveRecord
{
    protected $tablename = 'jurisdictions';

	/**
	 * Throws an exception if anything's wrong
	 * @throws Exception $e
	 */
	public function validate()
	{
		// Check for required fields here.  Throw an exception if anything is missing.
		if (!$this->getName()) {
			throw new Exception('missingRequiredFields');
		}

	}

	public function save() { parent::save(); }

	//----------------------------------------------------------------
	// Generic Getters and Setters
	//----------------------------------------------------------------
	public function getId  () { return parent::get('id'  ); }
	public function getName() { return parent::get('name'); }

	public function setName($s) { parent::set('name', $s); }

	public function handleUpdate(array $post)
	{
        $this->setName($post['name']);
	}


	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
	public function __toString() { return $this->getName(); }
}
