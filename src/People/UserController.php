<?php
/**
 * @copyright 2012-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\People;

use Blossom\Classes\Block;

class UsersController
{
	public function index(array $params)
	{
		$people = new PeopleTable();
		$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
		$users = $people->find(['user_account'=>true], ['lastname'], 20, $page);

		return new Views\UserListView(['users'=>$users]);
	}

	public function update(array $params)
	{
        if (!empty($_REQUEST['id'])) {
            try { $person = new Person($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else {
            $person = new Person();
        }

        if (isset($person)) {
            if (isset($_POST['username'])) {
                try {
                    $person->handleUpdateUserAccount($_POST);
                    // We might have populated this person's information from LDAP
                    // We need to do a new lookup in the system, to see if a person
                    // with their email address already exists.
                    // If they already exist, we should add the account info to that
                    // person record.
                    if (!$person->getId() && $person->getEmail()) {
                        try {
                            $existingPerson = new Person($person->getEmail());
                            $existingPerson->handleUpdateUserAccount($_POST);
                        }
                        catch (\Exception $e) { }
                    }

                    if (isset($existingPerson)) { $existingPerson->save(); }
                    else { $person->save(); }

                    header('Location: '.BASE_URL.'/users');
                    exit();
                }
                catch (\Exception $e) {
                    $_SESSION['errorMessages'][] = $e;
                }
            }

            return new Views\UserUpdateView(['user' => $person]);
        }
        else {
            return new \Applications\Views\NotFoundView();
        }
	}

	public function delete(array $params)
	{
		try {
			$person = new Person($_REQUEST['id']);
			$person->deleteUserAccount();
			$person->save();
		}
		catch (\Exception $e) {
			$_SESSION['errorMessages'][] = $e;
		}
		header('Location: '.View::generateUrl('users.index'));
		exit();
	}
}
