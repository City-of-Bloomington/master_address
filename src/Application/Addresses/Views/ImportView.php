<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\Metadata;
use Domain\Streets\UseCases\Info\InfoResponse;

class ImportView extends Template
{
    public function __construct(InfoResponse $info,
                               ?array        $csvData,
                               ?Metadata     $metadata,
                               ?array        $errors)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $vars = [
            'street' => $info->street,
            'data'   => $csvData,
            'errors' => $errors,
            'statuses'        => $metadata->statuses(),
            'types'           => $metadata->types(),
            'jurisdictions'   => $metadata->jurisdictions(),
            'townships'       => $metadata->townships(),
            'location_types'  => $metadata->locationTypes(),
            'quarterSections' => $metadata->quarterSections(),
            'zipCodes'        => $metadata->zipCodes()
        ];

        $this->blocks = [
            new Block('streets/info.inc', ['street' => $info->street]),
            new Block('addresses/actions/importForm.inc', $vars)
        ];
    }
}
