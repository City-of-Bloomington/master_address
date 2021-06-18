<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Places\Views;

use Application\Block;
use Application\Template;

use Domain\Places\Actions\Update\Request;
use Domain\Places\Actions\Update\Response;
use Domain\Places\Metadata;

class UpdateView extends Template
{
    public function __construct(Request   $request,
                                ?Response $response,
                                Metadata  $place)
    {
        parent::__construct('default', 'html');

        if ($response && $response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $this->vars['title'] = $this->_('place_edit');

        $vars = [
            'title'      => $this->vars['title'],
            'categories' => $place->categories(),
            'entities'   => $place->entities(),
            'statuses'   => $place->statuses(),
            'types'      => $place->types(),
            'vicinities' => $place->vicinities()
        ];
        foreach ($request as $k=>$v) { $vars[$k] = is_string($v) ? parent::escape($v) : $v; }
        $this->blocks = [ new Block('places/updateForm.inc', $vars) ];
    }
}
