<?php
/**
 * @copyright 2012-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Authentication;

use Blossom\Classes\Database;
use Application\View;

use Domain\Auth\AuthenticationService;
use Domain\Users\Entities\User;
use Domain\Users\DataStorage\PdoUsersRepository;

class LoginController
{
	private $return_url;
	private $repo;
	private $auth;

	public function __construct()
	{
		$this->return_url = !empty($_REQUEST['return_url']) ? $_REQUEST['return_url'] : BASE_URL;
		$this->repo = new PdoUsersRepository(Database::getConnection());
		$this->auth = new AuthenticationService($this->repo);
	}

	/**
	 * Attempts to authenticate users via CAS
	 */
	public function index(array $params)
	{
		// If they don't have CAS configured, send them onto the application's
		// internal authentication system
		if (!defined('CAS')) {
			header('Location: '.View::generateUrl('login.login').'?return_url='.$this->return_url);
			exit();
		}

		require_once CAS.'/CAS.php';
		\phpCAS::client(CAS_VERSION_2_0, CAS_SERVER, 443, CAS_URI, false);
		\phpCAS::setNoCasServerValidation();
		\phpCAS::forceAuthentication();
		// at this step, the user has been authenticated by the CAS server
		// and the user's login name can be read with phpCAS::getUser().

		// They may be authenticated according to CAS,
		// but that doesn't mean they have person record
		// and even if they have a person record, they may not
		// have a user account for that person record.
		try {
            $this->auth->identify(\phpCAS::getUser());
            $_SESSION['USER'] = $this->auth->identify(\phpCAS::getUser());

			header("Location: {$this->return_url}");
			exit();
		}
		catch (\Exception $e) {
			$_SESSION['errorMessages'][] = $e;
		}

		return new LoginView(['return_url' => $this->return_url]);
	}

	/**
	 * Attempts to authenticate users based on AuthenticationMethod
	 */
	public function login(array $params)
	{
		if (isset($_POST['username'])) {
			try {
                $_SESSION['USER'] = $this->auth->authenticate($_POST['username'], $_POST['password']);
                header('Location: '.$this->return_url);
                exit();
			}
			catch (\Exception $e) {
				$_SESSION['errorMessages'][] = $e;
			}
		}
		return new LoginView(['return_url'=>$this->return_url]);
	}

	public function logout(array $params)
	{
		session_destroy();
		header('Location: '.$this->return_url);
		exit();
	}
}
