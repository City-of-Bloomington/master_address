<?php
/**
 * @copyright 2012-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Controllers;
use Blossom\Classes\Controller;

class Index extends Controller
{
	public function index(array $params)
	{
        return new \Application\Views\IndexView();
	}
}
