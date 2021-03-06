<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application;

use Aura\Di\Container;

class Controller
{
    public const ITEMS_PER_PAGE = 20;
    protected $di;

    public function __construct(Container $container)
    {
        $this->di = $container;
    }

    /**
     * Reads a date field out of the request and converts it to a DateTime
     */
    public static function readDate(string $field): \DateTime
    {
        if (!empty($_REQUEST[$field])) {
            try { $start_date = new \DateTime($_REQUEST[$field]); }
            catch (\Exception $e) { $_SESSION['errorMessages'] = ['invalidDate']; }
        }
        if (!isset($start_date)) { $start_date = new \DateTime(); }

        return $start_date;
    }

    protected function address(int $address_id): ?\Domain\Addresses\Entities\Address
    {
        $load = $this->di->get('Domain\Addresses\UseCases\Load\Load');
        $res  = $load($address_id);
        if ($res->errors) { $_SESSION['errorMessages'] = $res->errors; }
        return $res->address;
    }

    protected function addressInfo(int $address_id): \Domain\Addresses\UseCases\Info\InfoResponse
    {
        $info = $this->di->get('Domain\Addresses\UseCases\Info\Info');
        return $info($address_id);
    }

    protected function name(int $name_id): ?\Domain\Streets\Entities\Name
    {
        $load = $this->di->get('Domain\Streets\Names\UseCases\Load\Load');
        $res  = $load($name_id);
        if ($res->errors) { $_SESSION['errorMessages'] = $res->errors; }
        return $res->name;
    }

    protected function person(int $person_id): ?\Domain\People\Entities\Person
    {
        $load = $this->di->get('Domain\People\UseCases\Load\Load');
        $res  = $load($person_id);
        if ($res->errors) { $_SESSION['errorMessages'] = $res->errors; }
        return $res->person;
    }

    protected function street(int $street_id): ?\Domain\Streets\Entities\Street
    {
        $load = $this->di->get('Domain\Streets\UseCases\Load\Load');
        $res  = $load($street_id);
        if ($res->errors) { $_SESSION['errorMessages'] = $res->errors; }
        return $res->street;
    }

    protected function streetInfo(int $street_id): \Domain\Streets\UseCases\Info\InfoResponse
    {
        $info = $this->di->get('Domain\Streets\UseCases\Info\Info');
        $req  = new \Domain\Streets\UseCases\Info\InfoRequest($street_id);
        return $info($req);
    }


    protected function subunitInfo(int $subunit_id): \Domain\Subunits\UseCases\Info\InfoResponse
    {
        $info = $this->di->get('Domain\Subunits\UseCases\Info\Info');
        $req  = new \Domain\Subunits\UseCases\Info\InfoRequest($subunit_id);
        return $info($req);
    }
}
