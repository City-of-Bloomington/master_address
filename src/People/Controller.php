<?php
/**
 * @copyright 2012-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\People;

use Blossom\Classes\View;

class Controller
{
	public function index(array $params)
	{
		$table = new PeopleTable();

		$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
		$people = $table->find(null, ['lastname'], 20, $page);

		return new Views\ListView(['people'=>$people]);
	}

	public function view(array $params)
	{
        if (!empty($_REQUEST['id'])) {
            try { $person = new Person($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        if (isset($person)) {
            return new Views\InfoView(['person'=>$person]);
        }
        return new \Application\Views\NotFoundView();
	}

	public function update(array $params)
	{
        if (!empty($_REQUEST['id'])) {
            try { $person = new Person($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else { $person = new Person(); }

        if (isset($person)) {
            $_SESSION['return_url'] = !empty($_REQUEST['return_url']) ? urldecode($_REQUEST['return_url']) : '';

            if (isset($_POST['firstname'])) {
                $person->handleUpdate($_POST);
                try {
                    $person->save();

                    $return_url = !empty($_SESSION['return_url'])
                                ? $_SESSION['return_url']
                                : ($person->getId()
                                    ? parent::generateUrl('people.view', ['id'=>$person->getId()])
                                    : parent::generateUrl('people.view'));
                    unset($_SESSION['return_url']);
                    header("Location: $return_url");
                    exit();
                }
                catch (\Exception $e) {
                    $_SESSION['errorMessages'][] = $e;
                }
            }

            return new Views\UpdateView(['person' => $person]);
        }
        return new \Application\Views\NotFoundView();
	}
}
