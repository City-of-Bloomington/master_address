<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Reports\Views;

use Application\Block;
use Application\Template;

use Domain\Reports\Report;

class ReportView extends Template
{
    public function __construct(Report $report, array $request, ?array $response=null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $metadata = $report->metadata();

        if ($format == 'html') {
            $this->vars['title'] = parent::escape($metadata['title']);

            $this->blocks = [
                new Block('reports/form.inc', [
                    'title'   => $this->vars['title'],
                    'params'  => $metadata['params'],
                    'request' => $request,
                    'result'  => $response,
                    'report'  => $report
                ])
            ];
        }
        else {
            $this->vars['title'] = $metadata['name'];
            if ($response) {
                $this->blocks = [
                    new Block('reports/output.inc', ['result'=>$response])
                ];
            }
        }
    }
}
