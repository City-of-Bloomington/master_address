<?php
/**
 * @copyright 2014-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\Models\Addresses\Parser;
use PHPUnit\Framework\TestCase;

class AddressParserTest extends TestCase
{
	public function addressProvider()
	{
		return [
			['401 N Morton St', ['street_number'=>401, 'direction'=>'N', 'street_name'=>'Morton', 'streetType'=>'ST' ]],
			['410 W 4th',       ['street_number'=>410, 'direction'=>'W', 'street_name'=>'4th'                        ]],
			['410 S 4th',       ['street_number'=>410, 'direction'=>'S', 'street_name'=>'4th'                        ]],
			['410 s 4th',       ['street_number'=>410, 'direction'=>'S', 'street_name'=>'4th'                        ]],
			['123 5th',         ['street_number'=>123,                   'street_name'=>'5th'                        ]],
			['123 Somersbe',    ['street_number'=>123,                   'street_name'=>'Somersbe'                   ]],
			['1 3rd',           ['street_number'=>1,                     'street_name'=>'3rd'                        ]],
			['1 M',             ['street_number'=>1,                     'street_name'=>'M'                          ]],
			['Morton',          ['street_name'=>'Morton' ]],
			['North',           ['street_name'=>'North'  ]],
			['Indiana',         ['street_name'=>'Indiana']],
			['401 N Morton St 47403',   ['street_number'=>401,  'direction'=>'N', 'street_name'=>'Morton',   'streetType'=>'ST', 'zip'=>47403 ]],
			['12 A E Longwood Ct',      ['street_number'=>12,   'direction'=>'E', 'street_name'=>'Longwood', 'streetType'=>'CT', 'street_number_suffix'=>'A',]],
			['12A E Longwood Ct',       ['street_number'=>12,   'direction'=>'E', 'street_name'=>'Longwood', 'streetType'=>'CT', 'street_number_suffix'=>'A',]],
			['1401 N E Longwood Ct',    ['street_number'=>1401, 'direction'=>'E', 'street_name'=>'Longwood', 'streetType'=>'CT', 'street_number_suffix'=>'N',]],
			['1401 E Longwood Ct West', ['street_number'=>1401, 'direction'=>'E', 'street_name'=>'Longwood', 'streetType'=>'CT', 'postDirection'=>'W']],
			['1401 E Longwood Ct W',    ['street_number'=>1401, 'direction'=>'E', 'street_name'=>'Longwood', 'streetType'=>'CT', 'postDirection'=>'W']],
			['E Longwood Ct W',         [                       'direction'=>'E', 'street_name'=>'Longwood', 'streetType'=>'CT', 'postDirection'=>'W']],
			['410-34 Country Club Dr.', ['street_number'=>410,                    'street_name'=>'Country Club', 'streetType'=>'DR', 'street_number_suffix'=>'-34']],
			['1/2 201 N Morton',        ['street_number'=>201, 'direction'=>'N',  'street_name'=>'Morton', 'street_number_prefix'=>'1/2']],
			['A 201 N Morton',          ['street_number'=>201, 'direction'=>'N',  'street_name'=>'Morton', 'street_number_prefix'=>'A'  ]],
			['A 410 1/2 W 4th',         ['street_number'=>410, 'direction'=>'W',  'street_name'=>'4th',    'street_number_prefix'=>'A',  'street_number_suffix'=>'1/2']],
			['201   N   Morton',                     ['street_number'=>201,    'direction'=>'N', 'street_name'=>'Morton']],
			['123 5th St, Bloomington, In, 47403',   ['street_number'=>123,                      'street_name'=>'5th',              'streetType'=>'ST', 'city'=>'Bloomington', 'state'=>'In', 'zip'=>'47403']],
			['943 1/2 N  Jackson  ST',               ['street_number'=>'943',  'direction'=>'N', 'street_name'=>'Jackson',          'streetType'=>'ST', 'street_number_suffix'=>'1/2']],
			['2437 S Walnut Street Pike',            ['street_number'=>'2437', 'direction'=>'S', 'street_name'=>'Walnut Street',    'streetType'=>'PIKE']],
			['2437 Walnut Street Pike',              ['street_number'=>'2437',                   'street_name'=>'Walnut Street',    'streetType'=>'PIKE']],
			['623 s washington st',                  ['street_number'=>'623',  'direction'=>'S', 'street_name'=>'washington',       'streetType'=>'ST']],
			['4750 N State Road 37',                 ['street_number'=>'4750', 'direction'=>'N', 'street_name'=>'State Road 37']],
			['300 E State Road 45 46 Bypass Unit 2', ['street_number'=>'300',  'direction'=>'E', 'street_name'=>'State Road 45 46', 'streetType'=>'BYP', 'subunitType'=>'UNIT', 'subunitIdentifier'=>'2']]
		];
	}

	public function streetProvider()
	{
		return [
			['N Walnut', ['direction'=>'N', 'street_name'=>'Walnut']],
			['N Mor',    ['direction'=>'N', 'street_name'=>'Mor']]
		];
	}

	/**
	 * @dataProvider addressProvider
	 */
	public function testAddressParsing($input, $output)
	{
		$parse = Parser::parse($input);
		$this->assertEquals($output, $parse);
	}

	/**
	 * @dataProvider streetProvider
	 */
	public function testStreetParsing($input, $output)
	{
		$parse = Parser::parse($input, 'streetNameOnly');
		$this->assertEquals($output, $parse);
	}
}
