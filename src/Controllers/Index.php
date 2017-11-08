<?php
/**
 * @copyright 2012-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;
use Blossom\Classes\View;

class Index
{
	public function index(array $params)
	{
        header("Location: ".View::generateUrl('addresses.index'));
        exit();
	}
}
