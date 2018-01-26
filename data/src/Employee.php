<?php
/**
 * A class for working with a Directory webservice
 *
 * This class is written specifically for the City of Bloomington's
 * Directory webservice.  If you are going to be doing authentication
 * with your own webservice, you will probably need to customize
 * the this class.
 *
 * @copyright 2011-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Site;

use Blossom\Classes\Url;
use Domain\Auth\AuthenticationInterface;
use Domain\Auth\ExternalIdentity;

class Employee implements AuthenticationInterface
{
	private $config;

	public function __construct(array $config)
	{
        $this->config = $config;
	}

	public function identify(string $username): ?ExternalIdentity
	{
		$url = $this->config['server'].'/people/view?format=json;username='.$username;
		$response = Url::get($url);
		if ($response) {
            $json = json_decode($response, true);
            if (!$json)                { throw new \Exception('employee/invalidResponse'); }
            if (!empty($json->errors)) { throw new \Exception('ldap/unknownUser'        ); }

            return new ExternalIdentity($json);
		}
		else {
            throw new \Exception('ldap/unknownUser');
		}
	}

	public function authenticate(string $username, string $password): bool
	{
        return false;
	}
}
