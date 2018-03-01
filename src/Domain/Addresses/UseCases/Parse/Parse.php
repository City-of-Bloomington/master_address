<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Parse;

use Domain\Addresses\DataStorage\AddressesRepository;
use Domain\Addresses\Metadata;

class Parse
{
    const NUMBER_PREFIX  = 'street_number_prefix';
    const NUMBER         = 'street_number';
    const NUMBER_SUFFIX  = 'street_number_suffix';
    const DIRECTION      = 'direction';
    const STREET_NAME    = 'street_name';
    const STREET_TYPE    = 'streetType';
    const POST_DIRECTION = 'postDirection';
    const SUBUNIT_TYPE   = 'subunitType';
    const SUBUNIT_ID     = 'subunitIdentifier';
    const CITY           = 'city';
    const STATE          = 'state';
    const ZIP            = 'zip';
    const ZIP_PLUS4      = 'zipplus4';

    private $repo;
    private $metadata;

    public function __construct(AddressesRepository $repository)
    {
        $this->repo     = $repository;
        $this->metadata = new Metadata($repository);
    }

	private function getStreetTypes(): array
	{
        static $types = [];
        if (!$types) {
            foreach ($this->metadata->streetTypes() as $row) {
                $types[$row['name']] = $row['code'];
            }
        }
        return $types;
	}

	private function getSubunitTypes(): array
	{
        static $types = [];
        foreach ($this->metadata->subunitTypes() as $row) {
            $types[$row['name']] = $row['code'];
        }
        return $types;
	}

	/**
	 * Creates an associative array for the parts of an address for a given string
	 *
	 * Only the parts of the address that are given in the string are returned
	 * Example: $string = "410 W 4th"
	 * Returns: ['street_number'        => '410',
     *           'street_direction_code'=> 'W',
     *           'street_name'          => '4th'
	 *			]
	 *
	 * Example: $string = "401 N Morton St, Bloomington, IN"
	 * Returns: ['street_number'           => '401',
     *           'street_direction_code'   => 'N',
     *           'street_name'             => 'Morton',
     *           'street_type_suffix_code' => 'St',
     *           'city'                    => 'Bloomington',
     *           'state'                   => 'IN'
	 *		    ]
	 *
	 * The strategy here is to match parts of the address piecemeal.
	 * As each part is matched, it is removed from the input string;
	 * this reduces the complexity for subsequent matches.
	 * Also, this is generally an outside-in approach.  We try matching
	 * parts at the beginning and end of the string first.  Then, we
	 * work our way towards the middle of the string.
	 *
	 * @param string $string
	 * @return array
	 */
	public function __invoke(string $string, string $parseType='address')
	{
		$output = new ParseResponse();

		// Lookup table variables
		$cities       = $this->metadata->cities();
		$directions   = Metadata::$directions;
		$streetTypes  = $this->getStreetTypes();
		$subunitTypes = $this->getSubunitTypes();


		//echo "Starting with |$string|\n";
		$address = preg_replace('/[^a-z0-9\-\s\/]/i', '',  $string);
		$address = preg_replace('/\s+/',              ' ', $address);

		if ($parseType=='address') {
			//echo "Looking for number: |$address|\n";
			$fraction = '\d\/\d';
			$directionCodePattern = implode('', $directions);
			$numberPattern = "(?<prefix>$fraction\s|[A-Z]\s)?(?<number>\d+)(?<suffix>\s$fraction\s|\-\d+\s|\s?[A-Z]\s)?(?<direction>[$directionCodePattern]\s)?";

			if (preg_match("/^$numberPattern/i", $address, $matches)) {

				if (!empty($matches['prefix'])) { $output->{self::NUMBER_PREFIX} = trim($matches['prefix']); }
				$output->{self::NUMBER} = trim($matches['number']);

				if (!empty($matches['direction'])) {
					$output->{self::DIRECTION} = trim($matches['direction']);
					if (!empty($matches['suffix'])) {
						$output->{self::NUMBER_SUFFIX} = trim($matches['suffix']);
					}
				}
				elseif (!empty($matches['suffix'])) {
					$s = strtoupper(trim($matches['suffix']));
					if (in_array($s, $directions)) {
						$output->{self::DIRECTION} = $s;
					}
					else {
						$output->{self::NUMBER_SUFFIX} = $s;
					}
				}
				$address = trim(preg_replace("#^$matches[0]#i",'',$address));
			}

			//echo "Looking for Zip: |$address|\n";
			$zipPattern = '(?<zip>\d{5})(\-(?<zipplus4>\d{4}))?';
			if (preg_match("/\s$zipPattern\s?$/i",$address,$matches)) {
				$output->{self::ZIP} = trim($matches['zip']);
				if (isset($matches['zipplus4']) && $matches['zipplus4']) {
					$output->{self::ZIP_PLUS4} = trim($matches['zipplus4']);
				}
				$address = trim(preg_replace("/\s$zipPattern$/i",'',$address));
			}

			//echo "Looking for State: |$address|\n";
			if (preg_match("/\s(?<state>IN)\b/i",$address,$matches)) {
				$output->{self::STATE} = trim($matches['state']);
				$address = trim(preg_replace("/\s$matches[state]$/i",'',$address));
			}

			//echo "Looking for city: |$address|\n";
			$cityPattern = implode('|', $cities);
			if (preg_match("/\s(?<city>$cityPattern)$/i",$address,$matches)) {
				$output->{self::CITY} = trim($matches['city']);
				$address = trim(preg_replace("/\s$matches[city]$/i",'',$address));
			}

			//echo "Looking for subunit: |$address|\n";
			$subunitTypePattern = implode('|',array_merge($subunitTypes, array_keys($subunitTypes)));
			$subunitPattern = "(?<subunitType>$subunitTypePattern)(\-|\s)?(?<subunitIdentifier>\w+)";
			if (preg_match("/\s(?<subunit>$subunitPattern)$/i",$address,$matches)) {
				try {
                    $type = strtoupper($matches['subunitType']);
					$output->{self::SUBUNIT_TYPE} = in_array($type, $subunitTypes) ? $type : $subunitType[$type];
					$output->{self::SUBUNIT_ID} = $matches['subunitIdentifier'];
					$address = trim(preg_replace("/\s$matches[subunit]$/i",'',$address));
				}
				catch (\Exception $e) {
					// Just ignore anything that's not a known type
				}
			}
		}

		//echo "Looking for Street Name: |$address|\n";
		$fullDirectionPattern = implode('|',array_merge($directions, array_keys($directions)));
		$streetTypePattern = implode('|',array_merge($streetTypes, array_keys($streetTypes)));
		$streetPattern = "
		(
			(?<dir>$fullDirectionPattern)\s(?<type>$streetTypePattern)\b
			|
			((?<direction>$fullDirectionPattern)\s)?
			(
				(?<name>[\w\s]+)
				(\s(?<streetType>$streetTypePattern)\b)
				(\s(?<postdirection>$fullDirectionPattern)\b)?
				$
				|
				(?<streetName>[\w\s]+)
				(\s(?<postdir>$fullDirectionPattern))\b
				$
				|
				(?<street>[\w\s]+)
				(\s(?<stype>$streetTypePattern)\b)?
				(\s(?<pdir>$fullDirectionPattern)\b)?
				$
			)
		)
		";
		preg_match("/$streetPattern/ix",$address,$matches);
		//print_r($matches);
		foreach ($matches as $key=>$value) {
			if (!is_int($key) && trim($value)) {
				// The regular expression for street names required some duplication.
				// Since you cannot use the same named subpattern more than once,
				// we came up with different names for the same things in the regex.
				// We need to convert any of those names back into the real name
				switch ($key) {
					case 'dir':
					case 'direction':
                        $value = strtoupper($value);
                        if (            in_array($value, $directions)) { $output->{self::DIRECTION} = $value; }
                        elseif (array_key_exists($value, $directions)) { $output->{self::DIRECTION} = $directions[$value]; }
                        // Just ignore anything that's not a known direction
						break;

					case 'type':
					case 'streetType':
					case 'stype':
                        $value = strtoupper($value);
                        if (            in_array($value, $streetTypes)) { $output->{self::STREET_TYPE} = $value; }
                        elseif (array_key_exists($value, $streetTypes)) { $output->{self::STREET_TYPE} = $streetTypes[$value]; }
                        // Just ignore anything that's not a known street type
						break;

					case 'name':
					case 'street':
					case 'streetName':
						$output->{self::STREET_NAME} = $value;
						break;

					case 'postdirection':
					case 'postdir':
					case 'pdir':
                        $value = strtoupper($value);
                        if (            in_array($value, $directions)) { $output->{self::POST_DIRECTION} = $value; }
                        elseif (array_key_exists($value, $directions)) { $output->{self::POST_DIRECTION} = $directions[$value]; }
                        // Just ignore anything that's not a known direction
						break;
				}
			}
		}

		// Sanity Checking
		if (!isset($output->{self::STREET_NAME}) && isset($output->{self::STREET_TYPE})) {
			$output->{self::STREET_NAME} = $output->{self::STREET_TYPE};
			unset($output->{self::STREET_TYPE});
		}
		if (!empty($output->{self::NUMBER_SUFFIX}) && empty($output->{self::STREET_NAME})) {
			$output->{self::STREET_NAME} = $output->{self::NUMBER_SUFFIX};
			unset($output->{self::NUMBER_SUFFIX});
		}
		return $output;
	}
}
