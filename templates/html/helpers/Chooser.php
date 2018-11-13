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
		$this->template->addToAsset('scripts', BASE_URI.'/js/chooser.js');

		$callback_url = new Url(Url::current_url(BASE_HOST));
		if (isset($callback_url->callback_field)) { unset($callback_url->callback_field); }

		$chooserUrl->callback_field = $fieldname;
		$chooserUrl->callback_url   = $callback_url->__toString();

		if (isset($callback_url->$fieldname)) { unset($callback_url->$fieldname); }

		$html = "
		<input type=\"hidden\" name=\"{$fieldname}\" id=\"{$fieldId}\" value=\"$value\" />
		<span id=\"{$fieldId}-display\">$display</span>
		<a class=\"button chooser\"
			href=\"$chooserUrl\"
			onclick=\"CHOOSER.open(event, '$fieldId', '$chooserUrl');\">
			{$this->template->_('choose')}
		</a>
		<a class=\"reset button\" href=\"$callback_url\">{$this->template->_('reset')}</a>

		";
		return $html;
	}
}
