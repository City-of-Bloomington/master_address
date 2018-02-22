<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Templates\Helpers;

use Application\Helper;
use Application\Url;
use Application\View;

class Chooser extends Helper
{
	/**
	 * @param  string $fieldname  The name of the field
	 * @param  string $fieldId    The ID of the field
	 * @param  Url    $chooserUrl Url to the start of the chooser process
	 * @param  string $value      The currently chosen value
	 * @param  string $display    Human readable representation of the current value
	 * @return string
	 */
	public function chooser( string $fieldname,
                             string $fieldId,
                                Url $chooserUrl,
                            ?string $value   = null,
                            ?string $display = null )
    {
		#$this->template->addToAsset('scripts', BASE_URI.'/js/people/chooser.js');

		$return_url = new Url(Url::current_url(BASE_HOST));
		if (isset($return_url->callback_field)) { unset($return_url->callback_field); }
		
		$chooserUrl->return_url = $return_url->__toString();

		#$personChooser = BASE_URI."/people?callback_field=$fieldname;return_url=$return_url";

		$html = "
		<input type=\"hidden\" name=\"{$fieldname}\" id=\"{$fieldId}\" value=\"$value\" />
		<span id=\"{$fieldId}-name\">$display</span>
		<a class=\"button chooser\"
			href=\"$chooserUrl\"
			onclick=\"CHOOSER.open(event, '$fieldId');\">
			{$this->template->_('choose')}
		</a>
		";
		return $html;
	}
}
