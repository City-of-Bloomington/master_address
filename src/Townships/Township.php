<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Townships;

use Blossom\Classes\ActiveRecord;

class Township extends ActiveRecord
{
    protected $tablename = 'townships';

    // Maps this model's fieldnames to database column names
    // [field => column]
    public static $fieldmap = [
        'id'          => 'id',
        'name'        => 'name',
        'code'        => 'code',
        'quarterCode' => 'quarter_code'
    ];

	/**
	 * Throws an exception if anything's wrong
	 * @throws Exception $e
	 */
	public function validate()
	{
		// Check for required fields here.  Throw an exception if anything is missing.
		if (!$this->getName() || !$this->getCode()) {
			throw new Exception('missingRequiredFields');
		}

	}

	public function save() { parent::save(); }

	//----------------------------------------------------------------
	// Generic Getters and Setters
	//----------------------------------------------------------------
	public function getId  () { return parent::get('id'  ); }
	public function getName() { return parent::get('name'); }
	public function getCode() { return parent::get('code'); }
	public function getQuarterCode() { return parent::get('quarter_code'); }

	public function setName($s) { parent::set('name', $s); }
	public function setCode($s) { parent::set('code', $s); }
	public function setQuarterCode($s) { parent::set('quarter_code', $s); }

	public function handleUpdate(array $post)
	{
        foreach (array_keys(self::$fieldmap) as $f) {
            $set = 'set'.ucfirst($f);
            $this->$set($post[$f]);
        }
	}


	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
	public function __toString() { return $this->getName(); }
}
