<?php
/**
 * @copyright 2007-2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Zend_Paginator $this->pages
 * @param URL $this->url
 */
if (count($this->pages)) {
	$url = $this->url;

	echo '<ul class="pageNavigation">';

	// Show the Back button
	if (isset($this->pages->previous)) {
		$url->page = $this->pages->first;
		echo "<li><a href=\"$url\" class=\"first\">First</a></li>";

		$url->page = $this->pages->previous;
		echo "<li><a href=\"$url\" class=\"previous\">Back</a></li>";
	}
	// Show the page number links
	// Show only $maxNumLinks pages at a time
	foreach ($this->pages->pagesInRange as $page) {
		$url->page = $page;
		$class = ($page == $this->pages->current) ? 'class="current"' : '';
		echo "<li><a href=\"$url\" $class>$page</a></li>";
	}

	// Show the Next button
	if (isset($this->pages->next)) {
		$url->page = $this->pages->next;
		echo "<li><a href=\"$url\" class=\"next\">Next</a></li>";

		$url->page = $this->pages->last;
		echo "<li><a href=\"$url\" class=\"last\">Last</a></li>";
	}

	echo '</ul>';
}
